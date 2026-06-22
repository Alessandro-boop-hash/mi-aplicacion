document.addEventListener('alpine:init', () => {
    Alpine.data('pedidoStepper', (config) => ({
        step: config.hasErrors ? 3 : 1,
        isVendedor: config.isVendedor,
        hasCliente: config.hasCliente,
        modoCliente: config.modoCliente || 'existente',
        clienteId: config.clienteId || '',
        userEmail: config.userEmail || '',
        detalles: config.oldDetalles.map((d) => ({
            modelo: d.modelo || '',
            talla: d.talla || '',
            cantidad: parseInt(d.cantidad) || 1,
            precio_unitario: parseFloat(d.precio_unitario) || 0,
        })),
        montoPagado: parseFloat(config.oldMontoPagado) || 0,

        init() {
            if (!config.hasErrors) {
                const savedCart = localStorage.getItem('marte_cart');
                if (savedCart) {
                    try {
                        const cartItems = JSON.parse(savedCart);
                        if (cartItems && cartItems.length > 0) {
                            this.detalles = cartItems.map(item => ({
                                modelo: item.modelo,
                                talla: item.talla,
                                cantidad: parseInt(item.cantidad) || 12,
                                precio_unitario: parseFloat(item.precio_unitario) || 0,
                            }));
                            // Limpiar carrito para que no se recargue en futuras visitas limpias
                            localStorage.removeItem('marte_cart');
                        }
                    } catch (e) {
                        console.error('Error al cargar el carrito:', e);
                    }
                }
            }
        },

        get cantidadTotal() {
            return this.detalles.reduce((sum, l) => sum + (parseInt(l.cantidad) || 0), 0);
        },
        get precioTotal() {
            return this.detalles.reduce((sum, l) => sum + this.lineSubtotal(l), 0);
        },
        get anticipoRequerido() {
            return Math.round(this.precioTotal * 0.5 * 100) / 100;
        },
        get saldoEstimado() {
            const pagado = parseFloat(this.montoPagado) || 0;
            return Math.max(0, Math.round((this.precioTotal - pagado) * 100) / 100);
        },
        get cantidadValida() {
            return this.cantidadTotal >= 12;
        },
        get anticipoValido() {
            return (parseFloat(this.montoPagado) || 0) >= this.anticipoRequerido && this.anticipoRequerido > 0;
        },

        lineSubtotal(linea) {
            return (parseInt(linea.cantidad) || 0) * (parseFloat(linea.precio_unitario) || 0);
        },
        addLinea() {
            this.detalles.push({ modelo: '', talla: '', cantidad: 1, precio_unitario: 0 });
        },
        removeLinea(index) {
            if (this.detalles.length > 1) {
                this.detalles.splice(index, 1);
            }
        },
        step1Valid() {
            if (this.isVendedor) {
                if (this.modoCliente === 'existente') {
                    return this.clienteId !== '';
                }

                return document.getElementById('cliente_nombre')?.value.trim() !== ''
                    && document.getElementById('cliente_numero_documento')?.value.trim() !== '';
            }

            if (this.hasCliente) {
                return true;
            }

            return document.getElementById('cliente_nombre')?.value.trim() !== ''
                && document.getElementById('cliente_numero_documento')?.value.trim() !== '';
        },
        step2Valid() {
            if (!this.cantidadValida) {
                return false;
            }

            return this.detalles.every((l) =>
                l.modelo.trim() !== ''
                && l.talla.trim() !== ''
                && (parseInt(l.cantidad) || 0) >= 1
                && (parseFloat(l.precio_unitario) || 0) > 0
            );
        },
        canSubmit() {
            return this.cantidadValida && this.anticipoValido && this.step2Valid();
        },
        nextStep() {
            if (this.step === 1 && this.step1Valid()) {
                this.step = 2;
            } else if (this.step === 2 && this.step2Valid()) {
                if (!this.montoPagado || this.montoPagado < this.anticipoRequerido) {
                    this.montoPagado = this.anticipoRequerido;
                }
                this.step = 3;
            }
        },
        beforeSubmit(event) {
            if (!this.canSubmit()) {
                event.preventDefault();
                return;
            }

            const form = event.target;

            // Si el formulario ya tiene el token asignado y listo para enviar, permitimos el submit normal.
            if (form.dataset.readyToSubmit === 'true') {
                return;
            }

            // De lo contrario, detenemos el submit para abrir la ventana de pago de Culqi.
            event.preventDefault();

            if (!window.Culqi) {
                alert('La pasarela de pagos Culqi no se ha cargado correctamente.');
                return;
            }

            window.Culqi.settings({
                title: 'Taller Confección',
                currency: 'PEN',
                amount: Math.round(parseFloat(this.montoPagado) * 100),
                email: this.userEmail || 'cliente@tallerconfeccion.test',
            });

            window.Culqi.open();
        },
    }));

    // Definición global del callback de Culqi para procesar el token generado
    window.culqi = function () {
        if (window.Culqi && window.Culqi.token) {
            const token = window.Culqi.token.id;
            const tokenInput = document.getElementById('culqi_token');
            if (tokenInput) {
                tokenInput.value = token;
            }

            const form = document.querySelector('form');
            if (form) {
                form.dataset.readyToSubmit = 'true';
                form.submit();
            }
        } else if (window.Culqi && window.Culqi.error) {
            alert(window.Culqi.error.message || 'Error al procesar el pago con Culqi.');
        }
    };
});
