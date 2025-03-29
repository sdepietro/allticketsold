<div role="dialog" id="myModalId" class="modal fade" style="display: none;">
   {!! Form::open(array('url' => route('postCreateTicket', array('event_id' => $event->id)), 'class' => 'ajax')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    <i class="ico-ticket"></i>
                    @lang("ManageEvent.create_ticket")</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('title', trans("ManageEvent.ticket_title"), array('class'=>'control-label required')) !!}
                            {!!  Form::text('title', old('title'),
                                        array(
                                        'class'=>'form-control',
                                        'placeholder'=>trans("ManageEvent.ticket_title_placeholder")
                                        ))  !!}
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('price', trans("ManageEvent.ticket_price"), array('class'=>'control-label required')) !!}
                                    {!!  Form::text('price', old('price'),
                                                array(
                                                'class'=>'form-control',
                                                'placeholder'=>trans("ManageEvent.price_placeholder")
                                                ))  !!}


                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('quantity_available', trans("ManageEvent.quantity_available"), array('class'=>' control-label')) !!}
                                    {!!  Form::number('quantity_available', old('quantity_available'),
                                                array(
                                                'class'=>'form-control',
												'id' => 'quantity_available',
                                                'placeholder'=>trans("ManageEvent.quantity_available_placeholder")
                                                )
                                                )  !!}
                                </div>
                            </div>

                        </div>

                        <div class="form-group more-options" style="display: block;">
                            {!! Form::label('description', trans("ManageEvent.ticket_description"), array('class'=>'control-label')) !!}
                            {!!  Form::text('description', old('description'),
                                        array(
                                        'class'=>'form-control'
                                        ))  !!}
                        </div>

                        <div class="row more-options" style="display: block;">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    {!! Form::label('start_sale_date', trans("ManageEvent.start_sale_on"), array('class'=>' control-label')) !!}
                                    {!!  Form::text('start_sale_date', old('start_sale_date'),
                                                    [
                                                'class'=>'form-control start hasDatepicker ',
                                                'data-field'=>'datetime',
                                                'data-startend'=>'start',
                                                'data-startendelem'=>'.end',
                                                'readonly'=>''

                                            ])  !!}
                                </div>
                            </div>

                            <div class="col-sm-6 ">
                                <div class="form-group">
                                    {!!  Form::label('end_sale_date', trans("ManageEvent.end_sale_on"),
                                                [
                                            'class'=>' control-label '
                                        ])  !!}
                                    {!!  Form::text('end_sale_date', old('end_sale_date'),
                                            [
                                        'class'=>'form-control end hasDatepicker ',
                                        'data-field'=>'datetime',
                                        'data-startend'=>'end',
                                        'data-startendelem'=>'.start',
                                        'readonly'=>''
                                    ])  !!}
                                </div>
                            </div>
                        </div>

                        <div class="row more-options" style="display: block;">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('min_per_person', trans("ManageEvent.minimum_tickets_per_order"), array('class'=>' control-label')) !!}
                                    <!--{!! Form::selectRange('min_per_person', 1, 100, 1, ['class' => 'form-control']) !!}-->
									{!! Form::number('min_per_person', 0, [
										'class' => 'form-control',
										'placeholder' => trans("ManageEvent.min_per_person_placeholder"),
										'id' => 'min_per_person' // Añade un ID para el script
									]) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('max_per_person', trans("ManageEvent.maximum_tickets_per_order"), array('class'=>' control-label')) !!}
                                    <!--{!! Form::selectRange('max_per_person', 1, 100, 30, ['class' => 'form-control']) !!}-->
									{!! Form::number('max_per_person', 0, [
											'class' => 'form-control',
											'placeholder' => trans("ManageEvent.max_per_person_placeholder"),
											'id' => 'max_per_person' // ID para acceder en el script
										]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="row more-options" style="display: block;">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <div class="custom-checkbox">
                                        {!! Form::checkbox('is_hidden', 1, false, ['id' => 'is_hidden']) !!}
                                        {!! Form::label('is_hidden', trans("ManageEvent.hide_this_ticket"), array('class'=>' control-label')) !!}
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-12" style="display: none;">
                        <a href="javascript:void(0);" class="show-more-options">
                            @lang("ManageEvent.more_options")
                        </a>
                    </div>

                </div>

            </div> <!-- /end modal body-->
            <div class="modal-footer">
               {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
               {!! Form::submit(trans("ManageEvent.create_ticket"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
<script>
    function initializeValidation() {
        const quantityAvailableInput = document.getElementById('quantity_available');
        const minPerPersonInput = document.getElementById('min_per_person');

        minPerPersonInput.addEventListener('keypress', function(event) {
            const char = String.fromCharCode(event.which);
            const newValue = minPerPersonInput.value + char;
            const quantityAvailable = parseInt(quantityAvailableInput.value, 10);

            // Verificamos si el nuevo valor es mayor que quantity_available
            if (parseInt(newValue, 10) > quantityAvailable) {
                alert('El valor no puede ser mayor que la cantidad disponible.');
				minPerPersonInput.value = quantityAvailable;
                event.preventDefault(); // Previene el ingreso del carácter
            }
        });
		minPerPersonInput.addEventListener('change', function() {
            const quantityAvailable = parseInt(quantityAvailableInput.value, 10);
            const value = parseInt(minPerPersonInput.value, 10);

            // Verificamos si el nuevo valor es mayor que quantity_available
            if (value > quantityAvailable) {
                alert('El valor no puede ser mayor que la cantidad disponible.');
                minPerPersonInput.value = quantityAvailable; // Ajusta el valor al máximo permitido
            }
        });
    }

    // Usar jQuery para escuchar el evento de apertura del modal
    $(document).on('shown.bs.modal', '#myModalId', function () {
        initializeValidation(); // Llama a la función de validación al abrir el modal
    });
</script>

<script>
    function initializeValidation2() {
        const quantityAvailableInput = document.getElementById('quantity_available');
        const minPerPersonInput = document.getElementById('min_per_person');
        const maxPerPersonInput = document.getElementById('max_per_person');

        // Validación para min_per_person
        maxPerPersonInput.addEventListener('change', function() {
            const quantityAvailable = parseInt(quantityAvailableInput.value, 10);
            const minPerPerson = parseInt(minPerPersonInput.value, 10);
            const value = parseInt(maxPerPersonInput.value, 10);

            // Verificamos los límites
            if (value < minPerPerson) {
                alert('El valor no puede ser menor que minimo de entradas.');
                maxPerPersonInput.value = minPerPerson; // Ajusta al valor mínimo permitido
            } else if (value > quantityAvailable) {
                alert('El valor no puede ser mayor que cantidad disponible.');
                maxPerPersonInput.value = quantityAvailable; // Ajusta al valor máximo permitido
            }
        });
	
    }

    // Usar jQuery para escuchar el evento de apertura del modal
    $(document).on('shown.bs.modal', '#myModalId', function () {
        initializeValidation2(); // Llama a la función de validación al abrir el modal
    });
</script>

<script>

       // Función para cambiar el texto de la etiqueta <a>
        function cambiarNombre() {

            // Selecciona el primer elemento con las clases especificadas
            const boton = document.querySelector('.dtpicker-button.dtpicker-buttonSet');
	    const boton3 = document.querySelector('.dtpicker-title');
	    const boton2 = document.querySelector('.dtpicker-button.dtpicker-buttonClear');

	    const dateValueElement = document.querySelector('.dtpicker-value').style.display = 'none';


            // Asegúrate de que el elemento existe
            if (boton) {
                // Cambia el texto de la etiqueta <a>
                boton.textContent = 'Agregar';
            }
	   if (boton2) {
                // Cambia el texto de la etiqueta <a>
                boton2.textContent = 'Cancelar';
                boton2.style.backgroundColor = 'red';
            }
	   if (boton3) {
                // Cambia el texto de la etiqueta <a>
                boton3.textContent = 'Establecer fecha y hora';
    
            }
        }

        // Ejecuta la función cambiarNombre cada segundo (500 ms)
        setInterval(cambiarNombre, 100);
    </script>
<script>
    // Función para cambiar el texto de la etiqueta <a>
   /* function cambiarNombre() {
        const boton = document.querySelector('.dtpicker-button.dtpicker-buttonSet');
        const boton3 = document.querySelector('.dtpicker-title');
        const boton2 = document.querySelector('.dtpicker-button.dtpicker-buttonClear');

        // Asegúrate de que el elemento existe
        if (boton) {
            boton.textContent = 'Agregar';
        }
        if (boton2) {
            boton2.textContent = 'Cancelar';
            boton2.style.backgroundColor = 'red';
        }
        if (boton3) {
            boton3.textContent = 'Establecer fecha y hora';
        }
    }

    // Función para inicializar el observador
    function initializeObserver() {
        const targetNode = document.getElementById('DatePicker');

        const observer = new MutationObserver((mutationsList) => {
            for (let mutation of mutationsList) {
                if (mutation.type === 'attributes' && mutation.target.style.display === 'block') {
                    cambiarNombre(); // Llama a la función cuando el div es visible
                    observer.disconnect(); // Desconectar el observador después de ejecutar
                    break; // Sale del bucle una vez que se ha encontrado el div visible
                }
            }
        });

        const config = { attributes: true };
        observer.observe(targetNode, config); // Comienza a observar el nodo
    }

    // Simulando la apertura del DatePicker al hacer clic en los inputs
    document.getElementById('start_sale_date').addEventListener('click', function() {
        //document.getElementById('DatePicker').style.display = 'block'; // Muestra el DatePicker
        initializeObserver(); // Inicializa el observador después de mostrar el DatePicker
    });

    document.getElementById('end_sale_date').addEventListener('click', function() {
        //document.getElementById('DatePicker').style.display = 'block'; // Muestra el DatePicker
        initializeObserver(); // Inicializa el observador después de mostrar el DatePicker
    });*/
</script>
</div>
