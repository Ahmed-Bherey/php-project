let input = document.querySelector("#input"),
    span = document.querySelector(".span");


input.addEventListener("blur", () => {
    let regex = /^([a-zA-Z\d\.-]+)@(yahoo|info|gmail)\.com$/;

    if (regex.test(input.value)) {
        span.innerHTML = "Your email is valid";
        span.style.color = "green"
    } else {
        span.innerHTML = "Your email must contains '@'"
        span.style.color = "red"
    }
})