const nameField = document.getElementById("name");
const emailField = document.getElementById("email");
const messageField = document.getElementById("message");
const submitBtn = document.getElementById("submitBtn");
const result = document.getElementById("result");

/* Reusable Validation Function */
function isEmpty(value) {
    return value.trim() === "";
}

/* Keypress Validation */
nameField.addEventListener("keyup", () => {
    if (isEmpty(nameField.value)) {
        nameField.style.border = "2px solid red";
    } else {
        nameField.style.border = "2px solid green";
    }
});

/* Mouse Hover Highlight */
[nameField, emailField, messageField].forEach(field => {
    field.addEventListener("mouseover", () => {
        field.classList.add("highlight");
    });

    field.addEventListener("mouseout", () => {
        field.classList.remove("highlight");
    });
});

/* Double Click Submit */
submitBtn.addEventListener("dblclick", () => {

    if (
        isEmpty(nameField.value) ||
        isEmpty(emailField.value) ||
        isEmpty(messageField.value)
    ) {
        result.style.color = "red";
        result.innerText = "All fields required!";
        return;
    }

    result.style.color = "green";
    result.innerText = "Feedback Submitted Successfully ";
});
