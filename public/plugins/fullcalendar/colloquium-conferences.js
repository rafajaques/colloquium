$(document).ready(function() {

	$('#calendar').fullCalendar({
		editable: false,
		height:600,
		eventSources: [{
			url: '/conferences/jsCalendar',
			// Needs translation
			error: function() { alert('there was an error while fetching events!'); },
		}],
		// Needs translation
		monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
		monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
		dayNames: ['Domingo','Segunda','Terça','Quarta','Quinta','Sexta','Sábado'],
		dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
		buttonText: {
			today: 'hoje',
			month: 'mês',
			week: 'semana',
			day: 'dia'
		},
	})

});