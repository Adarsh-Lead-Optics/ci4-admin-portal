$(document).ready(function () {
  var validationRules = {
    email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
    mobile: /^[6-9]\d{9}$/,
    name: /^[a-zA-Z\s]{3,}$/,
    description: /^[\w\s.,()'"-]+$/,
    password: /^.{4,8}$/,
  };

  var errorMessages = {
    name: {
      required: "This field is required",
      pattern:
        "Only letters and spaces are allowed, and must have at least 3 letters",
      ifInteger: "Name must not contain numbers",
    },
    email: {
      required: "This field is required",
      pattern: "Please enter a valid email address",
    },
    mobile: {
      required: "This field is required",
      pattern: "Please enter a 10-digit mobile number",
      smallValue: "Please enter a valid mobile number",
    },
    description: {
      required: "This field is required",
      pattern:
        "Description must only contain letters, numbers, and common punctuation",
    },
    password: {
      required: "This field is required",
      pattern: "Password must have 4 to 8 digit long & only nummber allow",
    },
  };

  function validateField(field, value) {
    var fieldName = field.attr("name");
    var isValid = true;
    var regex = validationRules[fieldName];

    if (value.trim() === "") {
      field.next(".error").text(errorMessages[fieldName].required);
      isValid = false;
    } else if (regex && !regex.test(value)) {
      if (fieldName === "name" && /\d/.test(value)) {
        field.next(".error").text(errorMessages[fieldName].ifInteger);
      } else {
        field.next(".error").text(errorMessages[fieldName].pattern);
      }
      isValid = false;
    } else if (fieldName === "mobile" && parseInt(value) < 6) {
      field.next(".error").text(errorMessages[fieldName].smallValue);
      isValid = false;
    } else {
      field.next(".error").text("");
    }

    return isValid;
  }

  $(".my-input-all").on("blur", function () {
    validateField($(this), $(this).val());
  });

  $(".my-input-all").on("focus", function () {
    $(this).next(".error").text("");
  });

  $(".my-input-all").on("keyup", function () {
    validateField($(this), $(this).val());
  });

  $("#myForm").on("submit", function (event) {
    var isValid = true;
    $(".my-input-all").each(function () {
      if (!validateField($(this), $(this).val())) {
        isValid = false;
      }
    });

    if (!isValid) {
      event.preventDefault();
    }
  });
});