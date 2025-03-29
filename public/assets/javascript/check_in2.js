var checkinApp = new Vue({
    el: '#app',
    data: {
        attendees: [],
        searchTerm: '',
        isInit: false,
        isScanning: false,
        videoElement: document.querySelector('video#scannerVideo'),
        canvasElement: document.querySelector('canvas#QrCanvas'),
        canvasContext: document.querySelector('canvas#QrCanvas').getContext('2d'),
        QrTimeout: null
    },

    created: function () {
        this.fetchAttendees();
    },

    methods: {
        fetchAttendees: function () {
            // Lógica para obtener asistentes
        },

        showQrModal: function () {
            this.isScanning = true;
            this.initScanner();
        },

        initScanner: function () {
            var that = this;
            this.isScanning = true;

            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "environment"
                }
            }).then(function (stream) {
                that.videoElement.srcObject = stream;
                that.videoElement.play();
                that.captureQrToCanvas();
            }).catch(function (err) {
                console.error(err);
                alert('Error al acceder a la cámara. Asegúrate de tener los permisos necesarios.');
            });

            this.isInit = true;
        },

        captureQrToCanvas: function () {
            if (!this.isInit) {
                return;
            }

            this.canvasContext.clearRect(0, 0, this.canvasElement.width, this.canvasElement.height);
            this.canvasContext.drawImage(this.videoElement, 0, 0);

            try {
                qrcode.decode();  // Llama a tu lógica de decodificación aquí
            } catch (e) {
                console.error(e);
                this.QrTimeout = setTimeout(this.captureQrToCanvas.bind(this), 500);
            }
        },

        closeScanner: function () {
            clearTimeout(this.QrTimeout);
            this.isScanning = false;
            this.videoElement.srcObject.getTracks().forEach(track => track.stop());
            this.isInit = false;
            this.fetchAttendees();
        }
    }
});