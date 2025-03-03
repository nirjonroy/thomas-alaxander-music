@extends('frontend.app')
@section('title', 'Home')
@push('css')

@endpush
@section('content')

<div class="ms_content_wrapper padder_top8">
  <!---Header--->
 
  <!---index page--->
  <div class="ms_index_wrapper common_pages_space">
<div class="log-register-main py-5 px-4">
  <form action="{{url('register')}}" method="POST" class="needs-validation" novalidate>
      @csrf
      <div class="mb-3">
        <label for="name" class="form-label">Name </label>
        <input type="text" name="name" class="form-control" id="name" placeholder="name " required>
        
    </div>
      
      <div class="mb-3">
          <label for="email" class="form-label">Email </label>
          <input type="email" name="email" class="form-control" id="email" placeholder="Email " required>
          
      </div>
      <div>
        <label for="email" class="form-label">Your Phone</label>
        <input type="text" name="phone" id="email" class="form-control" placeholder="EX: 98*******" required="">
        @if ($errors->has('phone'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('phone') }}</strong>
        </span>
    @endif
    </div>
    <div>
        <label for="password" class="form-label">Password</label>

        <input type="password" class="form-control" id="psw" name="password" required="">
    </div>

    <div>
        <label for="confirm-password" class="form-label">Confirm password</label>

        <input type="password" id="psw" name="password_confirmation"  class="form-control" required>
        @if ($errors->has('password_confirmation'))
        <span class="help-block text-danger">
            <strong>{{ $errors->first('password_confirmation') }}</strong>
        </span>
    @endif
    </div>
      <button type="submit" class="btn btn-success btn-lg" style="width: 100%; height: 50px; font-size: 14px; font-weight:bold; margin: 5px;">submit</button>
      <div class="w-full text-center mt-4">
          <a class=""  href="{{url('login-user')}}" style="color: red; background: black;">Already have an account? Login !!!</a>
      </div>
  </form>

<div class="container">


  <div class="ms_index_wrapper common_pages_space">

  </div>
</div>







<div id="message" style="background: #f86b1e; margin-bottom:10px">
  <h3>Password must contain the following:</h3>
  <p id="letter" class="invalid">A <b>lowercase</b> letter</p>
  <p id="capital" class="invalid">A <b>capital (uppercase)</b> letter</p>
  <p id="number" class="invalid">A <b>number</b></p>
  <p id="length" class="invalid">Minimum <b>8 characters</b></p>
</div>




@endsection

@push('js')
<script>
var myInput = document.getElementById("psw");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

// When the user clicks on the password field, show the message box
myInput.onfocus = function() {
  document.getElementById("message").style.display = "block";
}

// When the user clicks outside of the password field, hide the message box
myInput.onblur = function() {
  document.getElementById("message").style.display = "none";
}

// When the user starts to type something inside the password field
myInput.onkeyup = function() {
  // Validate lowercase letters
  var lowerCaseLetters = /[a-z]/g;
  if(myInput.value.match(lowerCaseLetters)) {
    letter.classList.remove("invalid");
    letter.classList.add("valid");
  } else {
    letter.classList.remove("valid");
    letter.classList.add("invalid");
  }

  // Validate capital letters
  var upperCaseLetters = /[A-Z]/g;
  if(myInput.value.match(upperCaseLetters)) {
    capital.classList.remove("invalid");
    capital.classList.add("valid");
  } else {
    capital.classList.remove("valid");
    capital.classList.add("invalid");
  }

  // Validate numbers
  var numbers = /[0-9]/g;
  if(myInput.value.match(numbers)) {
    number.classList.remove("invalid");
    number.classList.add("valid");
  } else {
    number.classList.remove("valid");
    number.classList.add("invalid");
  }

  // Validate length
  if(myInput.value.length >= 8) {
    length.classList.remove("invalid");
    length.classList.add("valid");
  } else {
    length.classList.remove("valid");
    length.classList.add("invalid");
  }
}
</script>

@endpush
