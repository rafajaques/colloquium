$(function(){
	// Activate all links
	$("a[rel=speakers]").click(function(){
		// Get the HREF (catch ID and removes #)
		var id = $(this).attr("href").substring(1);
		
		// Make the ID global
		$.submission_id = id;
		
		// Shows loading icon
		// ...
		
		// Makes the magic happens :)
		refreshSpeakersList();
	});
	
	// Buttons inside modal
	$("#speaker_add").click(function(){
		var email = $("#speaker_email").val();
		if (!email)
		{
			$("#speaker_email").focus();
			return;
		}
		
		// First check if e-mail exists
		$.get('./js/helpers/email/check/' + email, function(user_id) {
			if (user_id == 0)
			{
				alert('Email não encontrado no banco. Solicite ao palestrante que se registre.');
				return;
			}
			else
			{
				// Then tries to add the speaker
				$.post('./js/helpers/speakers/add',
				{
					"submission_id": $.submission_id,
					"user_id": user_id
				},
				function(added){
					if (added != 0)
					{
						// Finally refresh the speakers list
						refreshSpeakersList();
						return;
					}
					else
					{
						alert('Aconteceu um erro ao tentar adicionar o palestrante. Verifique se o palestrante já não foi adicionado e tente novamente.');
					}
				});
			}
		});
	});
});

function refreshSpeakersList()
{
	$.getJSON('./js/helpers/speakers/list/' + $.submission_id, function(data){
		if (data != "")
		{
			// Open up modal window
			$("#speakers").modal();
			var firstTr = $("#speakers_table").find("tr:first");
			
			$("#speakers_table").empty().append(firstTr);
			
			for (var i = 0; i < data.length; i++)
			{
				var append	 = "<tr>";
				append		+= "<td>" + data[i].display_name + "</td>";
				append		+= "<td>" + data[i].email + "</td>";
				append		+= "<td>" + data[i].status + "</td>";
				if (data[i].main == 1)
					append	+= "<td>&nbsp;</td>";
				else
					append	+= "<td><i class=\"icon-remove\" onclick=\"alert('Not implemented yet');\"></i></td>";
				append		+= "</tr>";
				$("#speakers_table").append(append);
			}
			
			// Cleanup email
			$("#speaker_email").val("");
		}
		else
		{
			$("#speakers").modal();
			$("#modal-content").html("<p>Não foi possível buscar a lista de palestrantes.</p>");
		}
	});
}