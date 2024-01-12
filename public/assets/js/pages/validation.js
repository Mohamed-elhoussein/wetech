const email = document.getElementById('useremail');
const username = document.getElementById('username');
const password = document.getElementById('userpassword');
const btnRegister = document.getElementById('register');
const emailfeedback = document.querySelector('.emailfeedback');



btnRegister.addEventListener('click',function(){
       
    if(email.value.trim().length<4 || email.length>25  ){
        emailfeedback.classList.add('d-block');
        emailfeedback.innerHTML="email must be between 4 and 25 character";
    }

    if(username.value.trim().length<1 || username.length>25){
    
        
    }
    if(password.value.trim().length<1 || password.length>25){
   
        
    }
})
