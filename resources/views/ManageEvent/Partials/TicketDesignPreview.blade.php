{!! Html::style(asset('assets/stylesheet/ticket.css')) !!}
<style>
    .ticket {
        border: 1px solid {{$event->ticket_border_color}};
        background: {{$event->ticket_bg_color}} ;
        
        border-left-color: {{$event->ticket_border_color}} ;
    }
	
    .ticket .logo {
        border-left: 1px solid {{$event->ticket_border_color}};
        border-bottom: 1px solid {{$event->ticket_border_color}};
    }
</style>
<div class="ticket" style="width: 850px;display: flex;padding: 0;height: 300px !important;border-radius: 30px;">
<div class="franja" style="width: 30px;background-color:{{$event->pre_order_display_message}} ;">

</div>
<div style="height: 100%;width: 600px;background-image: url('{{ $event->post_order_display_message }}');background-size: contain;background-repeat: no-repeat;background-position: center;display: flex;flex-direction: column-reverse;padding: 30px;">
    
	<div style="width: 160px;text-align: center;
    font-weight: 600;
"><h4 class="asistente" style="color: {{$event->location_long}};font-weight: 600; font-size: 11pt;">Asistente<h4></div>
	<div style="width: 160px;text-align: center;font-size: 13pt;
    margin-bottom: 11px;
    font-weight: 600;
"><h4 class="fechae" style="color: {{$event->location_lat}};font-weight: 600; font-size: 11pt;">@lang("Ticket.demo_start_date_time")<h4></div>
	
	<div  style="width: 160px;text-align: center;"
	><h4 class="lugar" style="color: {{$event->google_tag_manager_code}};
    font-size: 14pt;
    font-weight: 600;
">LUGAR</h4></div>
	<br>
	<div style="width: 160px;text-align: center;">
	<h4 class="tipoentrada"  style="color: {{$event->ticket_sub_text_color}};font-weight: 600; font-size: 15pt;">GENERAL<h4>
	</div>
</div>

<div style="width: 220px;display: flex;flex-direction: column;justify-content: space-around;align-items: center;">
<div >
 <h4 class="precio" style="color: {{$event->ticket_text_color}};font-weight: 600;font-size: x-large;">$ XX,XX</h4>
</div>
<div>
<p>Escanear para su ingreso</p>
<!--- Parametros DNS2D::getBarcodeSVG($data, $type, $width, $height, $color, $background);  -->
       {!! DNS2D::getBarcodeSVG('hello', 'QRCODE', 7, 7, $event->location_google_place_id ?? '#000', '#FFFFFF') !!}
</div>
</div>
    
    @if($event->is_1d_barcode_enabled)
        <!--<div class="barcode_vertical">
            {!! DNS1D::getBarcodeSVG(12211221, "C39+", 1, 50) !!}
        </div>-->
    @endif
 
        <!--@lang("Ticket.footer")-->
  
</div>
