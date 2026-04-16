async function login() {

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value.trim();
    const message = document.getElementById("message");

    
    if (!email || !password) {
        message.style.color = "red";
        message.innerText = "All fields are required";
        return;
    }

    if (!email.includes("@")) {
        message.style.color = "red";
        message.innerText = "Enter valid email";
        return;
    }

    try {
        const response = await fetch("http://localhost:4000/login", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();

        if (data.success) {
            message.style.color = "green";
            message.innerText = "Login Successful ";
        } else {
            message.style.color = "red";
            message.innerText = data.message;
        }

    } catch (error) {
        message.style.color = "red";
        message.innerText = "Server Error";
    }
}
