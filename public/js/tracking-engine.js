class WoyukTrackingEngine {
    constructor(config) {
        this.activityId = config.activityId;
        this.sportType = config.sportType;
        this.startTime = typeof config.startTime === 'number' ? new Date(config.startTime) : new Date(config.startTime);
        this.csrfToken = config.csrfToken;

        // UI Elements
        this.timerElement = document.getElementById('live-timer');
        this.distanceElement = document.getElementById('live-distance');
        this.metricElement = document.getElementById('live-metric');
        this.gpsStatusElement = document.getElementById('gps-status');

        // State Tracking
        this.timerInterval = null;
        this.syncInterval = null;
        this.watchId = null;
        this.lastPosition = null;
        this.totalDistanceMeters = 0.0;
        this.durationSeconds = 0;
        
        // Queue data untuk dikirim ke server (Batching)
        this.unsyncedPoints = [];

        // LEAFLET MAP STATE
        this.map = null;
        this.userMarker = null;
        this.routePolyline = null;
        this.pathCoordinates = []; // Array pasangan [lat, lng] untuk Leaflet

        this.init();
    }

    init() {
        this.initMap();
        this.startTimer();
        this.startGpsTracking();
        this.startSyncLoop();
    }

    /**
     * 1. INISIALISASI PETA LEAFLET (DARK MODE TILE LAYER)
     */
    initMap() {
        const mapContainer = document.getElementById('map');
        if (!mapContainer || typeof L === 'undefined') return;

        // Default awal center (Indonesia) sebelum GPS dapat
        this.map = L.map('map', {
            zoomControl: false, // Sembunyikan tombol + - agar UI bersih
            attributionControl: false
        }).setView([-6.200000, 106.816666], 15);

        // Dark Mode Tile Layer dari CartoDB
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            maxZoom: 19,
            subdomains: 'abcd'
        }).addTo(this.map);

        // Inisialisasi Polyline (Gradiyen Oranye WOYuk!)
        this.routePolyline = L.polyline([], {
            color: '#f97316', // Orange-500
            weight: 5,
            opacity: 0.9,
            lineCap: 'round',
            lineJoin: 'round'
        }).addTo(this.map);
    }

    startTimer() {
        this.timerInterval = setInterval(() => {
            const now = new Date();
            this.durationSeconds = Math.floor((now - this.startTime) / 1000);

            const hours = String(Math.floor(this.durationSeconds / 3600)).padStart(2, '0');
            const minutes = String(Math.floor((this.durationSeconds % 3600) / 60)).padStart(2, '0');
            const seconds = String(this.durationSeconds % 60).padStart(2, '0');

            if (this.timerElement) {
                this.timerElement.innerText = `${hours}:${minutes}:${seconds}`;
            }

            this.updateLiveMetrics();
        }, 1000);
    }

    startGpsTracking() {
        if (!("geolocation" in navigator)) {
            alert("Browser tidak mendukung GPS Geolocation.");
            return;
        }

        const options = {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        };

        this.watchId = navigator.geolocation.watchPosition(
            (pos) => this.handleGpsSuccess(pos),
            (err) => this.handleGpsError(err),
            options
        );
    }

    handleGpsSuccess(position) {
        const coords = position.coords;
        const lat = coords.latitude;
        const lng = coords.longitude;

        // Update Indikator UI
        if (this.gpsStatusElement) {
            const acc = Math.round(coords.accuracy);
            if (acc <= 15) {
                this.gpsStatusElement.innerText = `GPS AKURAT (${acc}m)`;
                this.gpsStatusElement.className = "text-xs font-bold text-emerald-400 uppercase font-mono";
            } else if (acc <= 35) {
                this.gpsStatusElement.innerText = `GPS SEDANG (${acc}m)`;
                this.gpsStatusElement.className = "text-xs font-bold text-amber-400 uppercase font-mono";
            } else {
                this.gpsStatusElement.innerText = `GPS LEMAH (${acc}m)`;
                this.gpsStatusElement.className = "text-xs font-bold text-red-400 uppercase font-mono";
            }
        }

        if (coords.accuracy > 40.0) return; // Abaikan sinyal sangat buruk

        const currentPos = {
            latitude: lat,
            longitude: lng,
            altitude: coords.altitude || 0,
            speed: coords.speed || 0,
            accuracy: coords.accuracy,
            timestamp: new Date().toISOString()
        };

        // UPDATE PETA LEAFLET
        this.updateMapPosition(lat, lng);

        if (this.lastPosition) {
            const deltaDist = this.calculateHaversineDistance(
                this.lastPosition.latitude, this.lastPosition.longitude,
                lat, lng
            );

            const minMoveThreshold = coords.accuracy <= 15 ? 1.2 : 2.0;

            if (deltaDist >= minMoveThreshold) {
                this.totalDistanceMeters += deltaDist;
                this.lastPosition = currentPos;
                this.unsyncedPoints.push(currentPos);

                // Tambahkan titik ke garis Polyline Peta
                this.pathCoordinates.push([lat, lng]);
                if (this.routePolyline) {
                    this.routePolyline.setLatLngs(this.pathCoordinates);
                }
            }
        } else {
            this.lastPosition = currentPos;
            this.unsyncedPoints.push(currentPos);
            
            // Titik Pertama Rute
            this.pathCoordinates.push([lat, lng]);
            if (this.routePolyline) {
                this.routePolyline.setLatLngs(this.pathCoordinates);
            }
        }

        this.updateLiveMetrics();
    }

    /**
     * UPDATE MARKER & CAMERA PETA LEAFLET
     */
    updateMapPosition(lat, lng) {
        if (!this.map) return;

        const currentLatLng = [lat, lng];

        // Buat Marker Pengguna jika belum ada
        if (!this.userMarker) {
            const customIcon = L.divIcon({
                className: 'custom-gps-marker',
                html: `<div class="w-5 h-5 bg-orange-500 rounded-full border-2 border-white shadow-lg animate-ping"></div>
                       <div class="w-4 h-4 bg-orange-600 rounded-full border-2 border-white absolute top-0.5 left-0.5"></div>`,
                iconSize: [20, 20],
                iconAnchor: [10, 10]
            });

            this.userMarker = L.marker(currentLatLng, { icon: customIcon }).addTo(this.map);
            this.map.setView(currentLatLng, 17); // Zoom masuk saat koordinat pertama ketemu
        } else {
            this.userMarker.setLatLng(currentLatLng);
            this.map.panTo(currentLatLng); // Geser kamera ke koordinat terkini
        }
    }

    calculateHaversineDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
                  
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    updateLiveMetrics() {
        const kmDisplay = (this.totalDistanceMeters / 1000).toFixed(2).replace('.', ',');
        if (this.distanceElement) {
            this.distanceElement.innerText = kmDisplay;
        }

        if (this.sportType === 'cycling') {
            const speedKmh = this.durationSeconds > 0 ? ((this.totalDistanceMeters / 1000) / (this.durationSeconds / 3600)).toFixed(1) : '0.0';
            if (this.metricElement) this.metricElement.innerText = speedKmh;
        } else {
            if (this.totalDistanceMeters > 0) {
                const paceSecPerKm = this.durationSeconds / (this.totalDistanceMeters / 1000);
                const pMin = String(Math.floor(paceSecPerKm / 60)).padStart(2, '0');
                const pSec = String(Math.floor(paceSecPerKm % 60)).padStart(2, '0');
                if (this.metricElement) this.metricElement.innerText = `${pMin}:${pSec}`;
            } else {
                if (this.metricElement) this.metricElement.innerText = "00:00";
            }
        }
    }

    startSyncLoop() {
        this.syncInterval = setInterval(() => {
            this.flushUnsyncedPoints();
        }, 5000);
    }

    flushUnsyncedPoints() {
        if (this.unsyncedPoints.length === 0) return;

        const payload = {
            points: [...this.unsyncedPoints],
            total_distance_meters: this.totalDistanceMeters,
            duration_seconds: this.durationSeconds
        };

        this.unsyncedPoints = [];

        fetch(`/api/activity/${this.activityId}/sync`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.csrfToken
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const calElem = document.getElementById('live-calories');
                const elevElem = document.getElementById('live-elevation');
                
                if (calElem) calElem.innerText = data.calories_burned || 0;
                if (elevElem) elevElem.innerText = Math.round(data.elevation_gain_m || 0);
            }
        })
        .catch(err => {
            console.error("Sync Error:", err);
        });
    }

    stopTracking() {
        if (this.timerInterval) clearInterval(this.timerInterval);
        if (this.syncInterval) clearInterval(this.syncInterval);
        if (this.watchId !== null && "geolocation" in navigator) {
            navigator.geolocation.clearWatch(this.watchId);
        }
        this.flushUnsyncedPoints();
    }

    handleGpsError(error) {
        if (this.gpsStatusElement) {
            this.gpsStatusElement.innerText = "GPS Error / Izin Ditolak";
            this.gpsStatusElement.className = "text-xs font-bold text-red-400 uppercase font-mono";
        }
    }
}