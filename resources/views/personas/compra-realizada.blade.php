@extends('personas.layouts.app')
@section('content')
    <div class="containerEdit bgMainObras">

        <div class="my-12">
            <div class="container text-white py-section bgCardObra">
              <div class="grid grid-cols-12 md:gap-4 gap-y-10">

				@foreach($orders as $order)
               <div class="col-span-12 md:col-span-9">
                <div class="space-y-5">
                  <h2 class="text-3xl font-medium">Compra realizada con éxito</h2>
                  <div class="grid grid-cols-3 gap-4"><div>
                  <h3 class="text-xl font-medium">Datos de la Funcion</h3>
                  <div class="mt-2 space-y-3 "><div>
                    <span class="block mr-3 text-xs  text-emerald-500">Nombre</span>
                    <div class="text-sm">{{ $order->event->title ?? 'Sin nombre' }}.</div>
                  </div>
                    <div>
                      <span class="block mr-3 text-xs  text-emerald-500">Duracion</span>
                      <div class="text-sm">{{ $order->event->location_post_code }} mins</div>
                    </div>
                      <div>
                        <span class="block mr-3 text-xs  text-emerald-500">Direccion</span>
                        <div class="text-sm">
                        <div>{{ $order->event->teatro->nombre }}</div>
                      </div>
                      </div>
                        <div>

                        </div>
                      </div>
                      </div>
                      <div>
                          <h3 class="text-xl font-medium">Datos de Pago</h3>
                          <div class="mt-2 space-y-3 ">
                            <div>
							@php

								$date =  $order->created_at;
								$formattedDate = \Carbon\Carbon::parse($date)->locale('es')->translatedFormat('l, d \d\e M \d\e Y, H:i:s');
							@endphp
                            <span class="block mr-3 text-xs  text-emerald-500">Fecha de compra</span>
                            <div class="text-sm">{{ $formattedDate }}</div>
                          </div>


                                <div>
                                  <a class="btn btn-primary" target="_blank"
                   href="{{route('showOrderTickets', ['order_reference' => $order->id])}}?download=1"><span class="block px-4 py-2 mr-3 text-sm font-bold text-white bg-red-600 rounded-full  w-max">Imprimir Boleto</span></a>
                                </div>
								<div style="margin-top: 20px;">
								<a style="margin-left: 8px;text-decoration: underline;" href="{{ route('personas.miscompras') }}">Ir a Mis Compras</a>
								</div>
                                </div>
                                </div>
                                </div>

                                  </div>
                                  </div>

								  <div>
								  <img class="logoMainHeader" src="{{ config('app.url') }}/recursos/public/success.png"style="
    width: 200px !important;height: 200px !important;
    max-width: 200px !important;" ></img>
								  </div>

								  @endforeach
              </div>
            </div>
        </div>


<script>
window.onload = function() {
    var isAuthenticated = @json(Auth::guard('clientes')->check());
    console.log(isAuthenticated); // true o false

    if (isAuthenticated) {
        console.log('El cliente está autenticado');
    } else {
        // Guardar la URL en localStorage antes de redirigir al login
        let currentUrl = "{{ route('personas.miscompras') }}";
        localStorage.setItem('currentUrl2', currentUrl); // Guarda la URL en localStorage
		let urlGuardada = localStorage.getItem('currentUrl2');
		 console.log(urlGuardada);

        // Redirigir al login
        //window.location.href = "{{ route('personas.login.form') }}";
    }
}
</script>

@endsection
