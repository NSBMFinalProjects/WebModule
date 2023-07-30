const nameFeild = document.getElementById("name:feild");
const emailFeild = document.getElementById("email:feild");
const messageFeild = document.getElementById("message:feild");

const nameError = document.getElementById("name:error");
const emailError = document.getElementById("email:error");
const messageError = document.getElementById("message:error");

const form = document.getElementsByTagName("form")[0];
const submitBtn = document.getElementById("submit");

const showError = (err, msg) => {
  err.style.display = "flex";
  err.innerText = msg;
};

const dismissError = (err) => {
  err.style.display = "none";
  err.innerText = "";
};

function isNameValid() {
  const value = nameFeild.value;
  const show = (msg) => showError(nameError, msg);
  const dismiss = () => dismissError(nameError);

  if (value.length === 0) {
    dismiss();
    show("name cannot be empty");
    return false;
  }

  if (value.length < 2) {
    dismiss();
    show("cannot be smaller than 2 characters");
    return false;
  }

  if (value.length > 30) {
    dismiss();
    show("cannot be larger than 30 characters");
    return false;
  }

  dismiss();
  return true;
}

function isEmailValid() {
  const value = emailFeild.value;
  const show = (msg) => showError(emailError, msg);
  const dismiss = () => dismissError(emailError);

  if (value.length === 0) {
    dismiss();
    show("email address cannot be empty");
    return false;
  }

  const emailRegex =
    /^[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?$/i;
  if (!emailRegex.test(value)) {
    dismiss();
    show("given email address is not valid");
    return false;
  }

  dismiss();
  return true;
}

function isMessageValid() {
  const value = messageFeild.value;
  const show = (msg) => showError(messageError, msg);
  const dismiss = () => dismissError(messageError);

  if (value.length === 0) {
    dismiss();
    show("message cannot be empty");
    return false;
  }

  if (value.length < 5) {
    dismiss();
    show("cannot be smaller than 5 characters");
    return false;
  }

  if (value.length > 500) {
    dismiss();
    show("cannot be larger than 500 characters");
    return false;
  }

  dismiss();
  return true;
}

nameFeild.addEventListener("change", isNameValid);
emailFeild.addEventListener("change", isEmailValid);
messageFeild.addEventListener("change", isMessageValid);

form.addEventListener("submit", async function (event) {
  event.preventDefault();

  if (!isNameValid()) {
    return;
  }
  if (!isEmailValid()) {
    return;
  }
  if (!isMessageValid()) {
    return;
  }

  const setButton = (text, color) => {
    var btnText = text;
    var btnColor = color;
    if (!btnText) {
      btnText = "Send Message";
    }
    if (!btnColor) {
      btnColor = "#0594EA";
    }

    submitBtn.innerText = btnText;
    submitBtn.style.backgroundColor = btnColor;
  };

  const res = await fetch("/contact", {
    method: "POST",
    body: JSON.stringify({
      name: nameFeild.value,
      email: emailFeild.value,
      message: messageFeild.value,
    }),
  });
  if (res.status === 200) {
    setButton("Submited!, We will get in touch with you shortly", "#05ce91");
    return;
  }

  console.error(await res.json());
  setButton("Something went wrong", "red");
});
