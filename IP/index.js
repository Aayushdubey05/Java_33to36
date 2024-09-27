const signup = document.getElementById('submission-form');
signup.addEventListener('submit',(event) => {
    event.preventDefault();
    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;

    if(email && password){
        window.location.href = 'reg.html'; 
    }
    else{
        alert("Please fill your gmail id and password");
    }
});
