const registrationForm = document.querySelector("#registration-form");

if (registrationForm) {
	const message = document.querySelector("#registration-message");
	const fields = {
		name: registrationForm.elements.namedItem("name"),
		email: registrationForm.elements.namedItem("email"),
		mobile: registrationForm.elements.namedItem("mobile"),
		password: registrationForm.elements.namedItem("password"),
		confirmPassword: registrationForm.elements.namedItem("confirmPassword"),
		course: registrationForm.elements.namedItem("course"),
		year: registrationForm.elements.namedItem("year"),
		gender: registrationForm.elements.namedItem("gender"),
		terms: registrationForm.elements.namedItem("terms")
	};

	const passwordToggles = Array.from(registrationForm.querySelectorAll("[data-password-toggle]")).map((toggle) => {
		const field = document.getElementById(toggle.getAttribute("aria-controls"));
		const showIcon = toggle.querySelector(".password-toggle-show");
		const hideIcon = toggle.querySelector(".password-toggle-hide");

		toggle.addEventListener("click", () => {
			const isVisible = field.type === "text";
			field.type = isVisible ? "password" : "text";
			toggle.setAttribute("aria-label", isVisible ? "Show password" : "Hide password");
			showIcon.hidden = !isVisible;
			hideIcon.hidden = isVisible;
		});

		return { field, toggle, showIcon, hideIcon };
	});

	const patterns = {
		name: /^(?=.{2,50}$)[A-Za-z]+(?:\s+[A-Za-z]+)*$/,
		email: /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/,
		mobile: /^(?:\+91[\s-]?)?[6-9]\d{9}$/,
		password: /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9\s])\S{8,32}$/
	};

	function validateField(fieldName) {
		const field = fields[fieldName];
		const value = field.value.trim();
		let error = "";

		if (fieldName === "name" && !patterns.name.test(value)) {
			error = "Enter a 2-50 character name using letters and spaces only.";
		} else if (fieldName === "email" && (!field.value || !patterns.email.test(field.value))) {
			error = "Enter a valid email address.";
		} else if (fieldName === "mobile" && !patterns.mobile.test(value)) {
			error = "Enter a valid 10-digit Indian mobile number, optionally prefixed with +91.";
		} else if (fieldName === "password" && !patterns.password.test(field.value)) {
			error = "Use 8-32 characters with uppercase, lowercase, a number, and a special character.";
		} else if (fieldName === "confirmPassword" && field.value !== fields.password.value) {
			error = "Passwords do not match.";
		} else if (["course", "year", "gender"].includes(fieldName) && !field.value) {
			error = "Please select an option.";
		} else if (fieldName === "terms" && !field.checked) {
			error = "You must accept the terms to register.";
		} else if (field.required && !field.value.trim()) {
			error = "This field is required.";
		}

		field.setCustomValidity(error);
		return !error;
	}

	registrationForm.addEventListener("input", (event) => {
		const fieldName = event.target.name;
		if (fieldName in fields && (event.target.value || event.target.validationMessage)) {
			validateField(fieldName);
		}
		if (fieldName === "password" && fields.confirmPassword.value) {
			validateField("confirmPassword");
		}
		message.textContent = "";
	});

	registrationForm.addEventListener("change", (event) => {
		const fieldName = event.target.name;
		if (fieldName in fields && (event.target.value || event.target.checked)) {
			validateField(fieldName);
		}
		message.textContent = "";
	});

	registrationForm.addEventListener("submit", (event) => {
		event.preventDefault();

		const invalidFieldNames = Object.keys(fields).filter((fieldName) => !validateField(fieldName));
		if (invalidFieldNames.length) {
			const firstInvalidField = fields[invalidFieldNames[0]];
			const fieldLabel = firstInvalidField.labels?.[0]?.textContent.replace("*", "").trim() || "registration details";
			firstInvalidField.reportValidity();
			firstInvalidField.focus();
			message.textContent = `${fieldLabel}: ${firstInvalidField.validationMessage}`;
			return;
		}

		message.textContent = "This demo does not save accounts. Create an account on the secure registration page.";
		const registrationLink = document.createElement("a");
		registrationLink.href = "register.php";
		registrationLink.textContent = " Open secure registration";
		message.append(registrationLink);
	});

	registrationForm.addEventListener("reset", () => {
		message.textContent = "";
		Object.values(fields).forEach((field) => field.setCustomValidity(""));
		passwordToggles.forEach(({ field, toggle, showIcon, hideIcon }) => {
			field.type = "password";
			toggle.setAttribute("aria-label", "Show password");
			showIcon.hidden = false;
			hideIcon.hidden = true;
		});
	});
}
