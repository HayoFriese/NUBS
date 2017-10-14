function _(x){
	return document.getElementById(x);
}

//TICKETS
	var t = 1;
				
	function addTicket() {
		t++;
		var div = document.createElement('div');
		var name = '<input type="text" id="ticketName" name="ticketName'+t+'"  data-group="ticket" placeholder="Ticket Name..."">';
		var flat = '<input type="number" step="0.01" min="0" id="ticketFlat" name="ticketFlat'+t+'" data-group="ticket" placeholder="Flat Price...">';
		var donate = '<input type="number" step="1" min="0" max="100" id="ticketDonate" name="ticketDonate'+t+'"  data-group="ticket" placeholder="% Tax for Donation...">';
		div.innerHTML = '<input type="button" onclick="removeTicket(this)" value="-">'+name+flat+donate;
		_('ticketList').appendChild(div);
	}

	function removeTicket(div){
		var ticketList = _('ticketList');
		ticketList.removeChild(div.parentNode);
		t--;
	}

//LINEUP
	var l = 1;
				
	function addLineup() {
		l++;
		var div = document.createElement('div');
		var lineAct = '<input type="text" id="lineupAct" name="lineupAct'+l+'" data-group="lineup" placeholder="Act...">';
		var lineVenue = '<input type="text" id="venue" name="venue'+l+'" data-group="lineup" placeholder="Venue...">';
		var lineTime = '<input type="time" id="lineupTime" name="lineupTime'+l+'" data-group="lineup" placeholder="Time...">';
		
		div.innerHTML = '<input type="button" onclick="removeLineup(this)" value="-">'+lineAct+lineVenue+lineTime;
		document.getElementById('lineupList').appendChild(div);
	}
	function removeLineup(div){
		var lineupList = document.getElementById('lineupList');
		lineupList.removeChild(div.parentNode);
		l--;
	}

//Process Data
	var title, conDate, time,
		addOne, addTwo, post, state, country,
		desc, ageR, lineupBool,
		mediaCover;

	var ticketName=[];
	var ticketFlat=[];
	var ticketDonate=[];
	var confirmTicket;

	var act=[];
	var venue=[];
	var lineupTime =[];
	var confirmLineup;

	function _(x){
		return document.getElementById(x);
	}

	function processSummary(){
		title = _("title").value;
		conDate = _("conDate").value;
		time = _("conTime").value;

		addOne =_("addOne").value;
		addTwo = _("addTwo").value;
		post = _("postCode").value;
		state = _("state").value;
		country = _("country").value;

		desc = _("conDesc").value;
		ageR = _("ageRestrict").value;

		lineupBool = _("lineupBool").checked;

		if(title.length > 2 && conDate && 
			time && addOne.length > 2 &&
			post.length > 5 && country.length > 2 &&
			desc.length > 2 && ageR >= 0){
			_("conSum").style.display = "none";
			_("conTicket").style.display = "block";
			_("progressCon").style.width = "40%";
			_("status").innerHTML = "Set Ticket Information";
		} else {
			alert("Please fill in all the fields.");
		}
	}

	function processTicket(){
		if(_("ticketList").innerHTML === ""){
			alert("Please add at least one ticket type");
		} else {
			var temp;
			
			var name = document.querySelectorAll("input#ticketName");
			var flat = document.querySelectorAll("input#ticketFlat");
			var donate = document.querySelectorAll("input#ticketDonate");
			//Ticket Name variable Isolation
				for (var e=0; e < name.length; e++){
					temp = name[e].value;
					ticketName.push(temp);
				}
			//Ticket Flat Price variable isolation
				for (var e=0; e < flat.length; e++){
					temp = flat[e].value;
					ticketFlat.push(temp);
				}
			//ticket donate% variable isolation
				for (var e=0; e < donate.length; e++){
					temp = donate[e].value;
					ticketDonate.push(temp);
				}

			if(ticketName.length > 0 && ticketFlat.length > 0 && 
				ticketDonate.length > 0){
				_("conTicket").style.display = "none";
				_("conMedia").style.display = "block";
				_("progressCon").style.width = "60%";
				_("status").innerHTML = "Upload Media";
			}else {
				alert("Please fill in all the ticket fields.");
			}
		}
	}

	function processMedia(){
		mediaCover = _("promo").value;
		mediaVid = _("vidLink").value;
		

		if(lineupBool === true && mediaCover){
			_("conMedia").style.display = "none";
			_("conLineup").style.display = "block";
			_("progressCon").style.width = "80%";
			_("status").innerHTML = "Edit Line Up";
		} else if(lineupBool === false && mediaCover){
			_("conMedia").style.display = "none";
			_("conConfirm").style.display = "block";
			_("progressCon").style.width = "100%";
			_("status").innerHTML = "Are These Details Correct?";

			_("display_title").innerHTML = title;
			_("display_conDate").innerHTML = conDate;
			_("display_conTime").innerHTML = time;
			_("display_addOne").innerHTML = addOne;
			_("display_addTwo").innerHTML = addTwo;
			_("display_post").innerHTML = post;
			_("display_state").innerHTML = state;
			_("display_country").innerHTML = country;
			_("display_desc").innerHTML = desc;
			_("display_ageR").innerHTML = ageR;
			_("display_lineupBool").innerHTML = "No";

			confirmTicket = _("display_tickets");
			for (var k=0; k < ticketName.length; k++){
				confirmTicket.innerHTML += "<div>";
				confirmTicket.innerHTML += "<p id='sumTickName'>"+ticketName[k]+"</p>";
				confirmTicket.innerHTML += "<p>Price: &pound;"+ticketFlat[k]+"</p>";
				confirmTicket.innerHTML += "<p>Donation Tax: "+ticketDonate[k]+"%</p>";
				confirmTicket.innerHTML += "</div>";
			}

			_("display_mediaCover").innerHTML = mediaCover; 
			_("display_mediaVid").innerHTML = mediaVid;
			_("display_lineup").style.display = "none";
		} else {
			alert("Please upload a promotional image, to be able to identify your Event.");
		}
	}

	function processLineup(){
		if(_("lineupList").innerHTML === ""){
			alert("Please add at least one act");
		} else {
			var lineAct = document.querySelectorAll("input#lineupAct");
			var lineVenue = document.querySelectorAll("input#venue");
			var lineTime = document.querySelectorAll("input#lineupTime");
			//Act variable Isolation
				for (var e=0; e < lineAct.length; e++){
					temp = lineAct[e].value;
					act.push(temp);
				}
			//Venue variable isolation
				for (var e=0; e < lineVenue.length; e++){
					temp = lineVenue[e].value;
					venue.push(temp);
				}
			//line up time variable isolation
				for (var e=0; e < lineTime.length; e++){
					temp = lineTime[e].value;
					lineupTime.push(temp);
				}


			if(act.length > 0 && venue.length > 0 && lineupTime.length > 0){
				_("conLineup").style.display = "none";
				_("conConfirm").style.display = "block";
				_("progressCon").style.width = "100%";
				_("status").innerHTML = "Are These Details Correct?";

				_("display_title").innerHTML = title;
				_("display_conDate").innerHTML = conDate;
				_("display_conTime").innerHTML = time;
				_("display_addOne").innerHTML = addOne;
				_("display_addTwo").innerHTML = addTwo;
				_("display_post").innerHTML = post;
				_("display_state").innerHTML = state;
				_("display_country").innerHTML = country;
				_("display_desc").innerHTML = desc;
				_("display_ageR").innerHTML = ageR;
				_("display_lineupBool").innerHTML = "Yes";
				
				confirmTicket = _("display_tickets");
				for (var k=0; k < ticketName.length; k++){
					confirmTicket.innerHTML += "<div>";
					confirmTicket.innerHTML += "<p id='sumTickName'>"+ticketName[k]+"</p>";
					confirmTicket.innerHTML += "<p>Price: &pound;"+ticketFlat[k]+"</p>";
					confirmTicket.innerHTML += "<p>Donation Tax: "+ticketDonate[k]+"%</p>";
					confirmTicket.innerHTML += "</div>";
				}

				_("display_mediaCover").innerHTML = mediaCover;
				_("display_mediaVid").innerHTML = mediaVid; 
				
				confirmLineup = _("display_lineup");
				for (var w=0; w < act.length; w++){
					confirmLineup.innerHTML += "<div>";
					confirmLineup.innerHTML += "<p id='sumAct'>"+act[w]+"</p>";
					confirmLineup.innerHTML += "<p>Venue: "+venue[w]+"</p>";
					confirmLineup.innerHTML += "<p>Start Time: "+lineupTime[w]+"</p>";
					confirmLineup.innerHTML += "</div>";
				}
			}else {
				alert("Please fill in all the line up fields.");
			}
		}
	}
	function postConcert(){
		_("newConcert").method = "post";
		_("newConcert").action = "concertPostParser.php";
		_("newConcert").submit();
	}