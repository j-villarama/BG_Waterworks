@extends('auth.layouts.app')

@section('content')

<style>
     body {
    margin: auto;
    font-family: -apple-system, BlinkMacSystemFont, sans-serif;
    overflow: auto;
    background: linear-gradient(315deg,rgba(238, 239, 32, 1)3%, rgba(128, 185, 24, 1)10%, rgba(60,132,206,1) 68%, rgba(255,25,25,1) 98%);
    animation: gradient 15s ease infinite;
    background-size: 400% 400%;
    background-attachment: fixed;
}

@keyframes gradient {
    0% {
        background-position: 0% 0%;
    }
    50% {
        background-position: 100% 100%;
    }
    100% {
        background-position: 0% 0%;
    }
}

.wave {
    background: rgb(255 255 255 / 25%);
    border-radius: 1000% 1000% 0 0;
    position: fixed;
    width: 200%;
    height: 12em;
    animation: wave 10s -3s linear infinite;
    transform: translate3d(0, 0, 0);
    opacity: 0.8;
    bottom: 0;
    left: 0;
    z-index: -1;
}

.wave:nth-of-type(2) {
    bottom: -1.25em;
    animation: wave 18s linear reverse infinite;
    opacity: 0.8;
}

.wave:nth-of-type(3) {
    bottom: -2.5em;
    animation: wave 20s -1s reverse infinite;
    opacity: 0.9;
}

@keyframes wave {
    2% {
        transform: translateX(1);
    }

    25% {
        transform: translateX(-25%);
    }

    50% {
        transform: translateX(-50%);
    }

    75% {
        transform: translateX(-25%);
    }

    100% {
        transform: translateX(1);
    }
}

.frm-login{
    height: 480px;
    width: 400px;
    background-color: rgba(255, 255, 255, 0.075);
    position: absolute;
    transform: translate(-50%,-50%);
    top: 50%;
    left: 50%;
    border-radius: 10px;
    backdrop-filter: blur(10px);
    border: rgba(255, 255, 255, 0.055);
    box-shadow: 0 0 20px rgba(8, 7, 16, 0.555);
    padding: 50px 35px;
}

.frm-login *{
    font-family: 'Poppins',sans-serif;
    color: #ffffff;
    letter-spacing: 0.5px;
    outline: none;
    border: none;
}

.frm-login h3{
    font-size: 32px;
    font-weight: 500;
    line-height: 42px;
    text-align: center;
}

.label-glass{
    display: block;
    margin-top: 30px;
    font-size: 16px;
    font-weight: 500;
}
.input-glass{
    display: block;
    height: 50px;
    width: 100%;
    background-color: rgba(20, 2, 2, 0.07);
    border-radius: 3px;
    padding: 0 10px;
    margin-top: 8px;
    font-size: 14px;
    font-weight: 300;
}
::placeholder{
    color: #e5e5e5;
}

.inputBx {
    position: relative;
    width: 100%;
    margin-bottom: 20px;
}

input {
      width: 100%;
      outline: none;
      border: none;
      border: 1px solid rgba(255, 255, 255, 0.2);
      background: rgba(255, 255, 255, 0.2);
      padding: 8px 10px;
      padding-left: 40px;
      border-radius: 10px;
      color: #fff;
      font-size: 16px;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }
    
    .password-control {
      position: absolute;
      top: 11px;
      right: 10px;
      display: inline-block;
      width: 20px;
      height: 20px;
      background: url(https://snipp.ru/demo/495/view.svg) 0 0 no-repeat;
      transition: 0.5s;
}
        
      .view {
         background: url(https://snipp.ru/demo/495/no-view.svg) 0 0 no-repeat;
        transition: 0.5s;
  }


  .bx {
      position: absolute;
      top: 13px;
      left: 13px;
    }



input[type="submit"] {
      background: #fff;
      color: #111;
      max-width: 100px;
      padding: 8px 10px;
      box-shadow: none;
      letter-spacing: 1px;
      cursor: pointer;
      transition: 1.5s;
    }

input[type="submit"]:hover {
      background: linear-gradient(115deg, 
        rgba(0,0,0,0.10), 
        rgba(255,255,255,0.25));
      color: #fff;
      transition: .5s;
}

input::placeholder {
      color: #fff;
    }
    
    span {
        position: absolute;
        left: 30px;
        padding: 10px;
        display: inline-block;
        color: #fff;
        transition: .5s;
        pointer-events: none;
      }
    
    input:focus ~ span,
    input:valid ~ span {
      transform: translateX(-30px) translateY(-25px);
      font-size: 12px;
    }
  


p {
    color: #fff;
    font-size: 12px;
    margin-top: 5px;
  
    
}

a {
      color: #fff;
    }
    
    a:hover {
      background-color: #000;
      background-image: linear-gradient(to right, #434343 0%, black 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
}


  
  .square {
    position: absolute;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
    box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    border-radius: 15px;
    animation: square 10s linear infinite;
    animation-delay: calc(-1s * var(--i));
  }
  
  @keyframes square {
    0%,100% {
      transform: translateY(-20px);
    }
    
    50% {
      transform: translateY(20px);
    }
  }
  
  .square:nth-child(1) {
    width: 100px;
    height: 100px;
    top: -15px;
    right: -45px;
  }
  
  .square:nth-child(2) {
    width: 150px;
    height: 150px;
    top: 35%;
    left: 26%;
    z-index: 2;
  }
  
  .square:nth-child(3) {
    width: 60px;
    height: 60px;
    bottom: 19%;
    right: 32%;
    z-index: 2;
  }
  
  .square:nth-child(4) {
    width: 50px;
    height: 50px;
    bottom: 23%;
    left: 29%;
  }
  
  .square:nth-child(5) {
    width: 50px;
    height: 50px;
    top: -15px;
    left: -25px;
  }
  
  .square:nth-child(6) {
    width: 85px;
    height: 85px;
    top: 165px;
    right: -155px;
    z-index: 2;
  }

</style>



    <div class="square" style="--i:0;"></div>
    <div class="square" style="--i:1;"></div>
    <div class="square" style="--i:2;"></div>
    <div class="square" style="--i:3;"></div>
    <div class="square" style="--i:4;"></div>
    <div class="square" style="--i:5;"></div>


<div class="row">
    <form class="frm-login">
        <h3 class="pb-5">Login</h3>
    
    <div class="pt-4">
        <div class="inputBx">
            <input type="text" required="required">
            <span>Email</span>
            <i class='bx bx-envelope'></i>
        </div>

        <div class="inputBx password">
            <input id="password-input" type="password" name="password" required="required">
            <span>Password</span>
            <a href="#" class="password-control" onclick="return show_hide_password(this);"></a>
            <i class='bx bx-key'></i>
        </div>
    </div>    
          
            <div class="inputBx pt-3 text-center">
                <input type="submit" value="Log in" disabled> 
            </div>
           
    </form>
</div>

<script>

function show_hide_password(target){
	var input = document.getElementById('password-input');
	if (input.getAttribute('type') == 'password') {
		target.classList.add('view');
		input.setAttribute('type', 'text');
	} else {
		target.classList.remove('view');
		input.setAttribute('type', 'password');
	}
	return false;
}

</script>
@endsection
