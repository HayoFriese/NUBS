var uname, pass, pcheck, 
	comp, fname, lname, email, gender, 
	day, month, year, dob, 
	adone, adtwo, post, state, country;
			
function _(x){
	return document.getElementById(x);
}
function nam(x){
	return document.getElementsByName(x);
}
//User			
	function userSignUp(){
		uname = _("userName").value;
		pass = _("passWord").value;
		pcheck = _("passCheck").value;
	
		if(pass != pcheck){
			alert("Those passwords do not match."); 
		}
		else {
			if(uname.length > 2 && pass.length > 2){
				_("logIn").style.display = "none";
				_("summary").style.display = "block";
				_("progress").style.width = "50%";
				_("status").innerHTML = "Enter Personal Details";
			} else {
				alert("Please fill in all the fields.");
			}
		}
	}

	function userSummary(){
		fname = _("firstName").value;
		lname = _("lastName").value;
		email = _("email").value;
		gender = _("gender").value;
		day = _("day").value;
		month = _("month").value;
		year = _("year").value;
	
		if(fname.length > 2 && lname.length > 2 && 
			email.length > 2 && gender.length > 2 && 
			day.length > 0 && month.length > 0 && 
			year.length == 4){
			
			_("summary").style.display = "none";
			_("address").style.display = "block";
			_("progress").style.width = "75%";
			_("status").innerHTML = "Enter Billing Address";
		}else{
			alert("Please fill in all the fields.");
		}
	}

	function userAddress(){
		adone = _("addOne").value;
		adtwo = _("addTwo").value;
		post = _("postCode").value;
		state = _("state").value;
		country = _("country").value;
	
		if(adone.length > 0 && post.length > 5 && state.length > 2 && country.length > 0){
			_("address").style.display = "none";
			_("confirm").style.display = "block";
			_("display_uname").innerHTML = uname;
			_("display_pass").innerHTML = pass;
			_("display_fname").innerHTML = fname;
			_("display_lname").innerHTML = lname;
			_("display_email").innerHTML = email;
			_("display_gender").innerHTML = gender;
			dob = day + "." + month + "." + year;
			_("display_dob").innerHTML = dob;
			_("display_adone").innerHTML = adone;
			_("display_adtwo").innerHTML = adtwo;
			_("display_post").innerHTML = post;
			_("display_state").innerHTML = state;
			_("display_country").innerHTML = country;
	
	
			_("progress").style.width = "100%";
			_("status").innerHTML = "Confirm Details";
		}else{
			alert("Please enter your address.");
		}
	}		
	
	function userSubmitForm(){
		_("signupUser").method = "post";
		_("signupUser").action = "signup.php";
		_("signupUser").submit();
	}

//Host
	function hostSignUp(){
		uname = _("userName").value;
		pass = _("passWord").value;
		pcheck = _("passCheck").value;
	
		if(pass != pcheck){
			alert("Those passwords do not match."); 
		}
		else {
			if(uname.length > 2 && pass.length > 2){
				_("logIn").style.display = "none";
				_("summary").style.display = "block";
				_("progress").style.width = "50%";
				_("status").innerHTML = "Contact Details of Representative";
			} else {
				alert("Please fill in all the fields.");
			}
		}
	}

	function hostSummary(){
		fname = _("firstName").value;
		lname = _("lastName").value;
		comp = _("company").value;
		email = _("email").value;
		gender = _("gender").value;
		day = _("day").value;
		month = _("month").value;
		year = _("year").value;
	
		if(fname.length > 2 && lname.length > 2 && 
			comp.length > 2 &&  email.length > 2 && 
			gender.length > 2 && day.length > 0 && 
			month.length > 0 && year.length == 4){
			
			_("summary").style.display = "none";
			_("address").style.display = "block";
			_("progress").style.width = "75%";
			_("status").innerHTML = "Company Address";
		}else{
			alert("Please fill in all the fields.");
		}
	}
	
	function hostAddress(){
		adone = _("addOne").value;
		adtwo = _("addTwo").value;
		post = _("postCode").value;
		state = _("state").value;
		country = _("country").value;
	
		if(adone.length > 0 && post.length > 5 && state.length > 2 && country.length > 0){
			_("address").style.display = "none";
			_("confirm").style.display = "block";
			_("display_uname").innerHTML = uname;
			_("display_pass").innerHTML = pass;
			_("display_fname").innerHTML = fname;
			_("display_lname").innerHTML = lname;
			_("display_email").innerHTML = email;
			_("display_gender").innerHTML = gender;
			dob = day + "." + month + "." + year;
			_("display_dob").innerHTML = dob;
			_("display_adone").innerHTML = adone;
			_("display_adtwo").innerHTML = adtwo;
			_("display_post").innerHTML = post;
			_("display_state").innerHTML = state;
			_("display_country").innerHTML = country;
	
	
			_("progress").style.width = "100%";
			_("status").innerHTML = "Confirm Submission Details";
		}else{
			alert("Please enter your address.");
		}
	}		
	
	function hostSubmitForm(){
		_("signupHost").method = "post";
		_("signupHost").action = "signup.php";
		_("signupHost").submit();
	}