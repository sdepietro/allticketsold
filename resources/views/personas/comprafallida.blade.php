@extends('personas.layouts.app')
@section('content')
    <div class="containerEdit bgMainObras">
        
        <div class="my-12">
            <div class="container py-section bgCardObra text-white">
              <div class="grid grid-cols-12 md:gap-4 gap-y-10">
                
		
               <div class="col-span-9 md:col-span-9">
                <div class="space-y-5">
                  <h2 class="text-3xl font-medium">No se pudo realizar la compra, por favor intente de nuevo</h2>
                  <div class="grid grid-cols-3 gap-4"><div>
                  <h3 class="text-xl font-medium"></h3>
                  <div class="mt-2 space-y-3 "><div>
                    <span class=" mr-3 block text-xs text-emerald-500"></span>
                    <div class="text-sm"></div>
                  </div>
                    <div>
                      <span class=" mr-3 block text-xs text-emerald-500"></span>
                      <div class="text-sm"></div>
                    </div>
                      <div>
                        <span class=" mr-3 block text-xs text-emerald-500"></span>
                        <div class="text-sm">
                        <div></div>
                      </div>
                      </div>
                        <div>

                        </div>
                      </div>
                      </div>
                      <div>
                          <h3 class="text-xl font-medium"></h3>
                          <div class="mt-2 space-y-3 ">
                           
                         
                                
                              
								<div style="margin-top: 20px;">
								<a style="margin-left: 8px;text-decoration: underline;" href="{{ route('personas.miscompras') }}">Ir a Mis Compras</a>
								</div>
                                </div>
                                </div>
                                </div>
                                 
                                  </div>
                                  </div>
								  
								  <div>
								  <img class="logoMainHeader" src="{{ config('app.url') }}/recursos/public/failed.png"style="
    width: 300px !important;height: 200px !important;
    max-width: 300px !important;" ></img>
								  </div>
								  
								
              </div>
            </div>
        </div>
        
@endsection
