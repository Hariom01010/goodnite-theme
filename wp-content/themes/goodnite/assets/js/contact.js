/**
 * Goodnite Contact Us Page Script
 * Interactivity: Client-side validation, AJAX fetch submission to admin-ajax.php,
 * double-submit prevention, error handling and success state feedback.
 *
 * @package Goodnite
 */

(() => {
  "use strict";

  const form = document.querySelector("[data-contact-form]");
  if (!form) return;

  const error = form.querySelector("[data-contact-error]");
  const success = form.querySelector("[data-contact-success]");
  const submitBtn = form.querySelector(".contact-submit");

  form.addEventListener("submit", async (event) => {
    event.preventDefault();

    const fields = [...form.querySelectorAll("input, select, textarea")].filter(
      (field) => field.name && field.name !== "goodnite_hp" && !field.name.startsWith("goodnite_contact_nonce")
    );

    const nameVal = form.elements.name ? form.elements.name.value.trim() : "";
    const phoneVal = form.elements.phone ? form.elements.phone.value.replace(/\D/g, "") : "";
    const emailVal = form.elements.email ? form.elements.email.value.trim() : "";
    const messageVal = form.elements.message ? form.elements.message.value.trim() : "";

    const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailVal);
    const phoneValid = phoneVal.length >= 10;
    const requiredFilled = Boolean(nameVal && emailVal && messageVal && phoneVal);

    const isValid = requiredFilled && emailValid && phoneValid;

    if (!isValid) {
      if (error) {
        if (!requiredFilled) {
          error.textContent = "Please complete all fields with valid details.";
        } else if (!emailValid) {
          error.textContent = "Please enter a valid email address.";
        } else if (!phoneValid) {
          error.textContent = "Please enter a valid mobile number with at least 10 digits.";
        } else {
          error.textContent = "Please complete all fields with valid details.";
        }
        error.hidden = false;
      }
      if (success) success.hidden = true;

      // Focus first empty or invalid field.
      const firstInvalid = fields.find((f) => !f.value.trim()) ||
        (!emailValid ? form.elements.email : (!phoneValid ? form.elements.phone : null));
      if (firstInvalid && typeof firstInvalid.focus === "function") {
        firstInvalid.focus();
      }
      return;
    }

    // Hide previous error and success messages.
    if (error) error.hidden = true;
    if (success) success.hidden = true;

    // Disable submit button during request.
    const originalBtnText = submitBtn ? submitBtn.innerHTML : "";
    if (submitBtn) {
      submitBtn.disabled = true;
    }

    // Prepare payload.
    const formData = new FormData(form);
    formData.set("action", "goodnite_submit_contact");

    const ajaxUrl =
      (window.goodnite_contact_params && window.goodnite_contact_params.ajax_url) ||
      form.getAttribute("action") ||
      "/wp-admin/admin-ajax.php";

    const nonce =
      (window.goodnite_contact_params && window.goodnite_contact_params.nonce) ||
      (form.elements["goodnite_contact_nonce"] ? form.elements["goodnite_contact_nonce"].value : "");

    if (nonce) {
      formData.set("nonce", nonce);
    }

    try {
      const response = await fetch(ajaxUrl, {
        method: "POST",
        body: formData,
        headers: {
          "X-Requested-With": "XMLHttpRequest",
        },
      });

      const result = await response.json();

      if (result && result.success) {
        if (success) {
          if (result.data && result.data.message) {
            success.textContent = result.data.message;
          }
          success.hidden = false;
        }
        if (error) error.hidden = true;
        form.reset();
      } else {
        const errMsg =
          (result && result.data && result.data.message) ||
          "An error occurred while submitting your inquiry. Please try again.";
        if (error) {
          error.textContent = errMsg;
          error.hidden = false;
        }
        if (success) success.hidden = true;
      }
    } catch (err) {
      if (error) {
        error.textContent = "A network error occurred. Please try again later.";
        error.hidden = false;
      }
      if (success) success.hidden = true;
    } finally {
      if (submitBtn) {
        submitBtn.disabled = false;
        if (originalBtnText) submitBtn.innerHTML = originalBtnText;
      }
    }
  });
})();

