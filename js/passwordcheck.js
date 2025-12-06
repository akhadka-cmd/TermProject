// Password Matching Validation
document.addEventListener("DOMContentLoaded", function () {
  const password = document.getElementById("password");
  const confirmPassword = document.getElementById("confirm_password");
  const msg = document.getElementById("passwordMessage");
  const submitBtn = document.getElementById("submitBtn");

  if (password && confirmPassword) {
    function validatePassword() {
      if (confirmPassword.value === "") {
        msg.innerHTML = "";
        submitBtn.disabled = false;
        return;
      }
      if (password.value === confirmPassword.value) {
        msg.innerHTML = "Passwords match";
        msg.style.color = "green";
        submitBtn.disabled = false;
      } else {
        msg.innerHTML = "Passwords do not match";
        msg.style.color = "red";
        submitBtn.disabled = true;
      }
    }

    password.addEventListener("keyup", validatePassword);
    confirmPassword.addEventListener("keyup", validatePassword);
  }
});
