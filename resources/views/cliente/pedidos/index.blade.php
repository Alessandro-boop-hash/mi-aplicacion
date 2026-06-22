<script src="https://checkout.culqi.com/js/v4"></script>

<div class="mt-6">
    <button type="button" id="btn_pagar" class="w-full bg-green-600 text-white py-2 px-4 rounded hover:bg-green-700">
        Pagar con Yape, Plin o Tarjeta
    </button>
</div>

<script>
    Culqi.publicKey = '{{ env('CULQI_PUBLIC_KEY') }}';
    
    Culqi.settings({
        title: 'Taller Confección',
        currency: 'PEN',
        amount: 31250,
    });

    const btn_pagar = document.getElementById('btn_pagar');
    btn_pagar.addEventListener('click', function (e) {
        Culqi.open();
    });

    function culqi() {
        if (Culqi.token) {
            const token = Culqi.token.id;
            console.log('Token generado:', token);
        }
    }
</script>