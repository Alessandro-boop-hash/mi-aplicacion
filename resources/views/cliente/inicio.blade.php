<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-2xl text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 uppercase tracking-widest leading-tight drop-shadow-sm">
            Inicio — Catálogo Premium
        </h2>
    </x-slot>

    <div class="py-12" x-data="shoppingCart()" x-init="init()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Catalog Section --}}
            <div id="catalogo" class="relative bg-slate-900 rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] p-6 sm:p-10 overflow-hidden border border-white/10">
                <!-- Background Glow Effects -->
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-500/20 rounded-full mix-blend-screen filter blur-[100px] animate-pulse pointer-events-none"></div>
                <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-500/20 rounded-full mix-blend-screen filter blur-[100px] animate-pulse pointer-events-none" style="animation-delay: 2s;"></div>

                <div class="relative z-10 mb-10 flex flex-col items-center text-center">
                    <h3 class="text-4xl sm:text-5xl font-black text-white uppercase tracking-tight mb-3">Colección <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Exclusiva</span></h3>
                    <p class="text-sm sm:text-base text-slate-400 font-medium tracking-wide max-w-2xl">Selecciona una prenda para ver su detalle, explorar texturas, elegir tallas y armar tu pedido de confección con diseño a medida.</p>
                </div>

                <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3 relative z-10">
                    
                    @php
                    $prendas = [
                        ['Polo Deportivo Fit', 35.00, 'Performance', 'Polo fitted con tecnología Dry-Fit para control de humedad y costuras planas anti-roce.', '/images/prendas/polo.png'],
                        ['Casaca Cortaviento', 75.00, 'Outdoor', 'Cortaviento ultra liviano con acabado impermeable y capucha regulable para entrenamientos externos.', '/images/prendas/casaca.png'],
                        ['Licra Pro Performance', 55.00, 'Pro-Fit', 'Calzas de compresión de alta elasticidad y soporte muscular, diseñadas para alto impacto.', '/images/prendas/licra.png'],
                        ['Short Runner Premium', 30.00, 'Running', 'Shorts de carrera ligeros con forro transpirable interior y perforaciones láser para ventilación.', '/images/prendas/short.png'],
                        ['Gorra Deportiva Marte', 25.00, 'Accesorios', 'Gorra ultraligera con tela respirable, banda absorbente interna y cierre regulable ergonómico.', '/images/prendas/gorra.png'],
                        ['Mochila Deportiva', 90.00, 'Accesorios', 'Mochila resistente al agua con compartimento separado para zapatillas y múltiples bolsillos de organización.', '/images/prendas/mochila.png'],
                        ['Camiseta Térmica Pro', 45.00, 'Térmica', 'Polera de manga larga con aislamiento térmico y ajuste de compresión para conservar el calor durante entrenamientos en climas fríos.', '/images/prendas/termica.png'],
                        ['Jogger Active Elite', 60.00, 'Performance', 'Pantalón jogger slim-fit con bolsillos impermeables con cremallera, puños elásticos y tejido elástico multidireccional ultra suave.', '/images/prendas/jogger.png'],
                        ['Medias Crew (Pack x3)', 18.00, 'Accesorios', 'Medias de alta amortiguación en talón y puntera con soporte de arco elástico y canales de ventilación de alto rendimiento.', '/images/prendas/medias.png']
                    ];
                    @endphp

                    @foreach($prendas as $prenda)
                    {{-- Tarjeta Glassmorphism --}}
                    <div class="group relative rounded-[2rem] bg-white/5 border border-white/10 backdrop-blur-md shadow-lg transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_15px_40px_rgba(6,182,212,0.15)] flex flex-col justify-between overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
                        
                        <div @click="openAddModal('{{$prenda[0]}}', {{$prenda[1]}}, '{{$prenda[2]}}', '{{$prenda[3]}}', '{{$prenda[4]}}')"
                             class="relative aspect-square w-full overflow-hidden bg-slate-800/30 cursor-pointer p-8 flex items-center justify-center">
                            <!-- Overlay glow on hover -->
                            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10 pointer-events-none"></div>
                            
                            <!-- Imagen con animación flotante -->
                            <img src="{{$prenda[4]}}" alt="{{$prenda[0]}}" 
                                 class="relative z-0 h-full w-full object-contain filter drop-shadow-[0_10px_20px_rgba(0,0,0,0.5)] group-hover:scale-110 group-hover:-rotate-2 transition-transform duration-500 ease-out">
                        </div>
                        
                        <div class="p-6 flex-1 flex flex-col justify-between relative z-20">
                            <div class="space-y-3">
                                <div class="flex justify-between items-start">
                                    <h4 @click="openAddModal('{{$prenda[0]}}', {{$prenda[1]}}, '{{$prenda[2]}}', '{{$prenda[3]}}', '{{$prenda[4]}}')"
                                        class="font-black text-white text-lg tracking-tight cursor-pointer group-hover:text-cyan-400 transition-colors leading-tight pr-2">
                                        {{$prenda[0]}}
                                    </h4>
                                    <span class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400 shrink-0">
                                        S/ {{ number_format($prenda[1], 2) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="inline-block px-3 py-1 text-[10px] font-black text-cyan-300 uppercase tracking-widest bg-cyan-500/10 border border-cyan-500/20 rounded-full shadow-[0_0_10px_rgba(6,182,212,0.2)]">
                                        {{$prenda[2]}}
                                    </span>
                                </div>
                                <p class="text-xs text-slate-400 leading-relaxed pt-1 font-medium line-clamp-2">
                                    {{$prenda[3]}}
                                </p>
                            </div>
                            <button @click="openAddModal('{{$prenda[0]}}', {{$prenda[1]}}, '{{$prenda[2]}}', '{{$prenda[3]}}', '{{$prenda[4]}}')" 
                                    class="w-full mt-6 bg-white/5 hover:bg-gradient-to-r hover:from-cyan-500 hover:to-blue-600 text-white font-black text-xs uppercase py-3.5 px-4 rounded-xl tracking-widest transition-all duration-300 shadow-[inset_0_0_0_1px_rgba(255,255,255,0.1)] hover:shadow-[0_0_20px_rgba(6,182,212,0.5)] hover:border-transparent">
                                Configurar Pedido
                            </button>
                        </div>
                    </div>
                    @endforeach

                </div>
            </div>
        </div>

        {{-- Floating Cart Button con efecto neón --}}
        <button @click="isCartOpen = true" 
                class="fixed bottom-8 right-8 z-40 flex h-16 w-16 items-center justify-center rounded-full bg-gradient-to-r from-cyan-500 to-blue-600 text-white shadow-[0_0_30px_rgba(6,182,212,0.6)] hover:shadow-[0_0_50px_rgba(6,182,212,0.8)] hover:scale-110 active:scale-95 transition-all duration-300 border border-white/20">
            <svg class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <template x-if="totalItems > 0">
                <span class="absolute -top-2 -right-2 flex h-7 w-7 items-center justify-center rounded-full bg-purple-600 border-2 border-slate-900 text-[11px] font-black text-white animate-bounce shadow-lg" 
                      x-text="totalItems"></span>
            </template>
        </button>

        {{-- Slide-over Cart Sidebar (Glassmorphism oscuro) --}}
        <div x-show="isCartOpen" 
             class="fixed inset-0 z-50 overflow-hidden" 
             style="display: none;" 
             role="dialog" aria-modal="true">
            <div class="absolute inset-0 overflow-hidden">
                <div x-show="isCartOpen" 
                     x-transition.opacity 
                     class="absolute inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity" 
                     @click="isCartOpen = false"></div>

                <div class="fixed inset-y-0 right-0 flex max-w-full pl-10">
                    <div x-show="isCartOpen" 
                         x-transition:enter="transform transition ease-out duration-500 sm:duration-700"
                         x-transition:enter-start="translate-x-full"
                         x-transition:enter-end="translate-x-0"
                         x-transition:leave="transform transition ease-in duration-500 sm:duration-700"
                         x-transition:leave-start="translate-x-0"
                         x-transition:leave-end="translate-x-full"
                         class="w-screen max-w-md bg-slate-900/95 backdrop-blur-2xl shadow-2xl border-l border-white/10 flex flex-col justify-between">
                        
                        <div class="flex-1 overflow-y-auto py-8 px-6">
                            <div class="flex items-start justify-between border-b border-white/10 pb-5 mb-6">
                                <h2 class="text-lg font-black text-white uppercase tracking-widest flex items-center gap-3">
                                    <svg class="w-6 h-6 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                    Tu Carrito
                                </h2>
                                <button type="button" @click="isCartOpen = false" class="text-slate-400 hover:text-white transition bg-white/5 rounded-full p-2 hover:bg-white/20">
                                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>

                            <div class="mt-6">
                                <template x-if="cart.length === 0">
                                    <div class="text-center py-16 space-y-6">
                                        <div class="mx-auto w-24 h-24 rounded-full bg-white/5 flex items-center justify-center">
                                            <svg class="h-10 w-10 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                        </div>
                                        <p class="text-sm font-bold uppercase tracking-wider text-slate-400">Tu carrito está vacío</p>
                                        <button @click="isCartOpen = false" class="inline-block px-6 py-3 rounded-xl bg-cyan-500/10 text-cyan-400 text-xs font-black uppercase tracking-widest hover:bg-cyan-500/20 transition">Explorar catálogo</button>
                                    </div>
                                </template>

                                <template x-if="cart.length > 0">
                                    <ul role="list" class="-my-6 divide-y divide-white/10">
                                        <template x-for="(item, index) in cart" :key="index">
                                            <li class="flex py-6 group">
                                                <div class="flex-1 space-y-2">
                                                    <div class="flex justify-between text-sm font-black text-white uppercase tracking-wide">
                                                        <h5 x-text="item.modelo" class="pr-4"></h5>
                                                        <p class="text-cyan-400" x-text="'S/ ' + (item.precio_unitario * item.cantidad).toFixed(2)"></p>
                                                    </div>
                                                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest bg-white/5 inline-block px-2 py-1 rounded-md">Talla: <span class="text-white font-black" x-text="item.talla"></span></p>
                                                    <div class="flex items-center justify-between text-xs pt-2 font-bold tracking-wider">
                                                        <p class="text-slate-500" x-text="item.cantidad + ' uds x S/ ' + item.precio_unitario.toFixed(2)"></p>
                                                        <button type="button" @click="removeFromCart(index)" class="font-black text-red-400 hover:text-red-300 hover:underline tracking-widest flex items-center gap-1 opacity-70 group-hover:opacity-100 transition">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                            Quitar
                                                        </button>
                                                    </div>
                                                </div>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                            </div>
                        </div>

                        <template x-if="cart.length > 0">
                            <div class="border-t border-white/10 bg-slate-950/50 p-6 space-y-5">
                                <div class="flex justify-between text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    <span>Unidades totales:</span>
                                    <span class="font-black text-white" x-text="totalItems + ' uds.'"></span>
                                </div>
                                <div class="flex justify-between text-lg font-black text-white uppercase tracking-widest">
                                    <span>Total estimado:</span>
                                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500" x-text="'S/ ' + totalPrice.toFixed(2)"></span>
                                </div>
                                <p class="text-[10px] text-slate-500 uppercase tracking-wide font-bold bg-white/5 p-3 rounded-lg border border-white/5">
                                    Estos productos se pre-cargarán en el formulario para registrar tu pedido final de confección.
                                </p>
                                <div class="grid grid-cols-2 gap-4 pt-2">
                                    <button @click="clearCart()" 
                                            class="w-full text-center py-4 rounded-xl bg-white/5 border border-white/10 text-xs font-black uppercase tracking-widest text-slate-300 hover:bg-white/10 hover:text-white transition duration-300">
                                        Vaciar
                                    </button>
                                    <button @click="checkout()" 
                                            class="w-full text-center py-4 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl text-xs font-black uppercase tracking-widest text-white shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:shadow-[0_0_30px_rgba(6,182,212,0.7)] hover:scale-[1.02] transition duration-300">
                                        Procesar
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        {{-- Add to Cart Configuration Modal (Premium Glassmorphism) --}}
        <div x-show="selectedProduct" 
             class="fixed inset-0 z-50 overflow-y-auto" 
             style="display: none;" 
             role="dialog" aria-modal="true">
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:block sm:p-0">
                <div x-show="selectedProduct" 
                     x-transition.opacity 
                     class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm transition-opacity" 
                     @click="selectedProduct = null"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="selectedProduct" 
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-slate-900 rounded-[2rem] border border-white/10 text-left overflow-hidden shadow-[0_30px_60px_rgba(0,0,0,0.6)] transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    
                    <div class="bg-slate-900 p-8">
                        <div class="flex justify-between items-start border-b border-white/10 pb-4 mb-6">
                            <span class="text-xs font-black uppercase tracking-widest text-cyan-400 bg-cyan-500/10 px-3 py-1 rounded-full border border-cyan-500/20" x-text="selectedProduct ? selectedProduct.category : ''"></span>
                            <button @click="selectedProduct = null" class="text-slate-400 hover:text-white transition bg-white/5 rounded-full p-2 hover:bg-white/20">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div class="flex flex-col md:flex-row gap-8">
                            <!-- Left Column: Image Gallery -->
                            <div class="w-full md:w-1/2 space-y-4">
                                <div class="aspect-square bg-slate-800/50 rounded-3xl border border-white/5 overflow-hidden relative flex items-center justify-center p-6 shadow-inner">
                                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-purple-500/10 mix-blend-overlay"></div>
                                    <img :src="selectedProduct ? selectedProduct.gallery[activeGalleryIndex] : ''" 
                                         class="h-full w-full object-contain filter drop-shadow-[0_20px_30px_rgba(0,0,0,0.6)] transition-all duration-700 ease-out"
                                         :class="activeGalleryIndex === 1 ? 'scale-125' : (activeGalleryIndex === 2 ? 'scale-110 -rotate-3' : 'scale-100')">
                                </div>
                                <!-- Gallery Selector Thumbnails -->
                                <div class="grid grid-cols-3 gap-3">
                                    <template x-for="(img, idx) in (selectedProduct ? selectedProduct.gallery : [])" :key="idx">
                                        <button @click="activeGalleryIndex = idx" 
                                                type="button"
                                                class="aspect-square bg-slate-800/50 rounded-xl transition-all overflow-hidden relative flex items-center justify-center p-2"
                                                :class="activeGalleryIndex === idx ? 'border-2 border-cyan-400 shadow-[0_0_15px_rgba(6,182,212,0.3)]' : 'border border-white/5 hover:border-white/20'">
                                            <img :src="img" class="h-full w-full object-contain"
                                                 :class="idx === 1 ? 'scale-125' : (idx === 2 ? 'scale-110 -rotate-3' : 'scale-100')">
                                            <!-- Indicator overlay -->
                                            <span class="absolute bottom-1 right-1 bg-slate-900/80 backdrop-blur border border-white/10 text-[8px] font-black text-white px-1.5 py-0.5 rounded uppercase tracking-wider" 
                                                  x-text="idx === 0 ? 'FRENTE' : (idx === 1 ? 'DETALLE' : 'PERFIL')"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                            
                            <!-- Right Column: Information, Sizes, Actions -->
                            <div class="w-full md:w-1/2 flex flex-col justify-between space-y-6">
                                <div class="space-y-5">
                                    <div>
                                        <h3 class="text-3xl sm:text-4xl font-black text-white uppercase tracking-tight leading-none mb-3 drop-shadow-md" x-text="selectedProduct ? selectedProduct.name : ''"></h3>
                                        <p class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500" x-text="'S/ ' + (selectedProduct ? selectedProduct.price.toFixed(2) : '0.00')"></p>
                                    </div>
                                    
                                    <div class="bg-white/5 p-4 rounded-2xl border border-white/5">
                                        <h4 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 flex items-center gap-2">
                                            <svg class="w-4 h-4 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Especificaciones
                                        </h4>
                                        <p class="text-sm text-slate-300 leading-relaxed font-medium" x-text="selectedProduct ? selectedProduct.description : ''"></p>
                                    </div>

                                    {{-- Talla Selection --}}
                                    <div>
                                        <label class="block text-[11px] font-black uppercase tracking-widest text-white mb-3">Escala de Tallas</label>
                                        <div class="grid grid-cols-4 gap-3">
                                            <template x-for="t in ['S', 'M', 'L', 'XL']">
                                                <button @click="selectedTalla = t" 
                                                        type="button"
                                                        class="py-3 text-center rounded-xl border text-sm tracking-wider transition-all duration-300 shadow-sm"
                                                        :class="selectedTalla === t ? 'border-cyan-400 bg-gradient-to-br from-cyan-500 to-blue-600 text-white font-black shadow-[0_0_15px_rgba(6,182,212,0.4)] scale-105' : 'border-white/10 text-slate-300 bg-white/5 hover:border-white/30 hover:bg-white/10 font-bold'"
                                                        x-text="t"></button>
                                            </template>
                                        </div>
                                    </div>

                                    {{-- Cantidad Input --}}
                                    <div>
                                        <label class="block text-[11px] font-black uppercase tracking-widest text-white mb-3">Unidades a confeccionar</label>
                                        <div class="flex items-center gap-4 bg-white/5 p-2 rounded-xl w-max border border-white/10">
                                            <button type="button" @click="selectedCantidad = Math.max(1, selectedCantidad - 1)" 
                                                     class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-white hover:bg-cyan-500 transition-colors font-black text-xl">
                                                 -
                                             </button>
                                             <input type="number" 
                                                    x-model.number="selectedCantidad" 
                                                    min="1" 
                                                    class="w-16 h-10 bg-transparent text-center border-none text-white focus:ring-0 text-xl font-black p-0">
                                             <button type="button" @click="selectedCantidad = selectedCantidad + 1" 
                                                     class="flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-white hover:bg-cyan-500 transition-colors font-black text-xl">
                                                 +
                                             </button>
                                         </div>
                                         <div class="mt-3 flex items-start gap-2 text-[10px] text-cyan-400/80 uppercase tracking-wide font-bold leading-relaxed bg-cyan-500/10 p-3 rounded-lg border border-cyan-500/20">
                                             <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                             <span>Nota: Para pedidos corporativos o equipos, se requiere un mínimo acumulado de 12 unidades en el carrito.</span>
                                         </div>
                                     </div>
                                </div>

                                <div class="pt-6 flex flex-col sm:flex-row-reverse gap-4 border-t border-white/10">
                                    <button type="button" @click="addToCart()" 
                                            class="w-full sm:w-auto flex-1 justify-center rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 px-8 py-4 text-xs font-black uppercase tracking-widest text-white shadow-[0_0_20px_rgba(6,182,212,0.4)] hover:shadow-[0_0_30px_rgba(6,182,212,0.6)] hover:scale-[1.02] transition-all duration-300">
                                        Agregar al pedido
                                    </button>
                                    <button type="button" @click="selectedProduct = null" 
                                            class="w-full sm:w-auto inline-flex justify-center rounded-xl border border-white/20 bg-white/5 hover:bg-white/10 px-8 py-4 text-xs font-black uppercase tracking-widest text-slate-300 transition-all duration-300">
                                        Volver
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Frontend Cart Logic Script --}}
    <script>
        function shoppingCart() {
            return {
                cart: [],
                isCartOpen: false,
                selectedProduct: null,
                selectedTalla: 'M',
                selectedCantidad: 12,
                activeGalleryIndex: 0,

                init() {
                    const savedCart = localStorage.getItem('marte_cart');
                    if (savedCart) {
                        try {
                            this.cart = JSON.parse(savedCart);
                        } catch (e) {
                            console.error('Error parsing cart:', e);
                        }
                    }
                },

                saveCart() {
                    localStorage.setItem('marte_cart', JSON.stringify(this.cart));
                },

                openAddModal(productName, price, category, description, image) {
                    this.selectedProduct = { 
                        name: productName, 
                        price: price,
                        category: category,
                        description: description,
                        image: image,
                        gallery: [
                            image,
                            image,
                            image
                        ]
                    };
                    this.activeGalleryIndex = 0;
                    this.selectedTalla = 'M';
                    this.selectedCantidad = 12; // Mínimo inicial recomendado
                },

                addToCart() {
                    if (!this.selectedProduct) return;

                    // Validar cantidad
                    const qty = parseInt(this.selectedCantidad) || 1;

                    // Buscar si ya existe la misma prenda y misma talla en el carrito
                    const existingItem = this.cart.find(
                        item => item.modelo === this.selectedProduct.name && item.talla === this.selectedTalla
                    );

                    if (existingItem) {
                        existingItem.cantidad += qty;
                    } else {
                        this.cart.push({
                            modelo: this.selectedProduct.name,
                            talla: this.selectedTalla,
                            cantidad: qty,
                            precio_unitario: this.selectedProduct.price
                        });
                    }

                    this.saveCart();
                    this.selectedProduct = null;
                    this.isCartOpen = true; // Abre el carrito para dar feedback visual
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                    this.saveCart();
                },

                clearCart() {
                    this.cart = [];
                    this.saveCart();
                },

                get totalItems() {
                    return this.cart.reduce((sum, item) => sum + item.cantidad, 0);
                },

                get totalPrice() {
                    return this.cart.reduce((sum, item) => sum + (item.cantidad * item.precio_unitario), 0);
                },

                checkout() {
                    this.saveCart();
                    window.location.href = "{{ route('cliente.pedidos.create') }}";
                }
            };
        }
    </script>
</x-app-layout>
