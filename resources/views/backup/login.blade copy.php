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
    height: 520px;
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
</style>



<div class="row">
    <form class="frm-login">
        <h3>Sign In</h3>

        <label class="label-glass" for="username">Username</label>
            <input class="input-glass" type="text" placeholder="Email or Phone" id="username">
     
        <label class="label-glass" for="password">Password</label>
            <input class="input-glass" type="password" placeholder="Password" id="password">

            <div class="inputBx">
                <input type="submit" value="Log in" disabled> 
            </div>
        
    
    </form>
</div>
@endsection
