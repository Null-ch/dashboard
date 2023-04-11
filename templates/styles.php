
<style>
body{
	margin: 0;
	padding: 0;
	display: flex;
	justify-content: center;
	align-items: center;
	min-height: 100vh;
	font-family: 'Jost', sans-serif;
	background: #d1cfcf;
}
.main{
	width: 350px;
	height: 500px;
	background: black;
	overflow: hidden;
	border-radius: 10px;
	box-shadow: 5px 20px 50px #000;
}
#chk{
	display: none;
}
.signup{
	position: relative;
	width:100%;
	height: 100%;
}
label{
	color: #fff;
	font-size: 2.3em;
	justify-content: center;
	display: flex;
	margin: 60px;
	font-weight: bold;
	cursor: pointer;
	transition: .5s ease-in-out;
}
input{
	width: 220px;
	height: 40px;
	background: #e0dede;
	justify-content: center;
	display: flex;
	margin: 20px auto;
	padding: 10px;
	border: none;
	outline: none;
	border-radius: 5px;
}
button{
	width: 220px;
	height: 40px;
	margin: 10px auto;
	justify-content: center;
	display: block;
	color: #fff;
	background: #423e3ec5;
	font-size: 1em;
	font-weight: bold;
	margin-top: 20px;
	outline: none;
	border: none;
	border-radius: 5px;
	transition: .2s ease-in;
	cursor: pointer;
}
button:hover{
	background: #6e6e6e;
}
.login{
	height: 460px;
	background: #9e9e9e;
	border-radius: 60% / 10%;
	transform: translateY(-180px);
	transition: .8s ease-in-out;
}
.login label{
	color: #00000091;
	transform: scale(.6);
}

#chk:checked ~ .login{
	transform: translateY(-500px);
}
#chk:checked ~ .login label{
	transform: scale(1);	
}
#chk:checked ~ .signup label{
	transform: scale(.6);
}
.message {
    margin: 10px;
}
.recovery{

    width: 350px;
	height: 500px;
	border-radius: 10px;
	box-shadow: 5px 20px 50px #000;
	margin: auto;
	justify-content: center;
	display: block;
	color: #fff;
	background: black   ;
	font-size: 1em;
	outline: none;
	border: none;
	border-radius: 5px;

}
p{
	color: #fff;
    margin-left: auto;
    margin-right: auto;
    margin-top: 5px;
    text-align: center;
}

</style>
