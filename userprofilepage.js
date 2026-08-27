// PAYMENT MODAL
var paymentModal = document.getElementById("paymentModal");
var addButton = document.getElementById("addPayment");
var closeButton = document.getElementsByClassName("closeModal")[0];
var modalContent = document.querySelector(".pmContent");

addButton.onclick = function () {
	paymentModal.classList.remove("hideModal");
	paymentModal.classList.add("showModal");
	paymentModal.style.display = "flex";
};

closeButton.onclick = function () {
	closepModal();
};

paymentModal.addEventListener("mousedown", function (e) {
	if (!modalContent.contains(e.target)) {
		closepModal();
	}
});

function closepModal() {
	paymentModal.classList.remove("showModal");
	paymentModal.classList.add("hideModal");
	setTimeout(() => {
		paymentModal.style.display = "none";
	}, 300);
}

// CARD NUMBER FORMAT
const cardNumber = document.getElementById('cardNumber');

cardNumber.addEventListener('input', () => {
	let value = cardNumber.value;
	value = value.replace(/\D/g, '');
	value = value.slice(0, 16);
	value = value.replace(/(.{4})/g, '$1 ').trim();
	cardNumber.value = value;
});

// EXPIRY DATE FORMAT
const cardExpiry = document.getElementById('cardExDate');

cardExpiry.addEventListener('input', () => {
	let value = cardExpiry.value;
	value = value.replace(/\D/g, '');
	value = value.slice(0, 4);

	if (value.length >= 3) {
		value = value.slice(0, 2) + '/' + value.slice(2);
	}

	cardExpiry.value = value;
});

function isValidCardNumber(value) {
	const digitsOnly = value.replace(/\s+/g, '');
	return /^\d{16}$/.test(digitsOnly);
}

function isValidExpiry(value) {
	const match = value.match(/^(\d{2})\/(\d{2})$/);
	if (!match) return false;

	const month = parseInt(match[1], 10);
	return month >= 1 && month <= 12;
}


// RATING MODAL
var ratingModal = document.getElementById("ratingModal");
var ratingModalContent = document.querySelector(".rmContent");
var cancelButton = document.getElementById("rateCancel");

// RATING VARIABLES
var stars = document.querySelectorAll('.star');
var ratingText = document.getElementById('ratingText');
var selectedRatingInput = document.getElementById('selectedRating');
var bookingIdInput = document.getElementById('bookingId');
var submitButton = document.getElementById('submitRate');

var currentRating = 0;

var ratingMessages = {
	1: "Terrible - Not satisfied at all.",
	2: "Poor - Below expectations.", 
	3: "Fair - Met expectations.",
	4: "Great - Exceeded expectations!",
	5: "Excellent - Outstanding!"
};

document.addEventListener('click', function(e) {
	if (e.target.matches('[data-booking-id]')) {
		resetRating();
		var bookingId = e.target.getAttribute('data-booking-id');
		bookingIdInput.value = bookingId;
		ratingModal.classList.remove("hideModal");
		ratingModal.classList.add("showModal");
		ratingModal.style.display = "flex";
	}
});

var ratingCloseButton = document.querySelectorAll('.closeModal')[1];

ratingCloseButton.onclick = function () {
	closerModal();
};

cancelButton.onclick = function () {
	closerModal();
};

ratingModal.addEventListener("mousedown", function (e) {
	if (!ratingModalContent.contains(e.target)) {
		closerModal();
	}
});

function closerModal() {
	ratingModal.classList.remove("showModal");
	ratingModal.classList.add("hideModal");
	setTimeout(() => {
		ratingModal.style.display = "none";
		resetRating();
	}, 300);
}

function resetRating() {
	currentRating = 0;
	selectedRatingInput.value = '';
	ratingText.textContent = 'Click on stars to rate';
	submitButton.disabled = true;
	
	stars.forEach(star => {
		star.classList.remove('active');
		star.style.color = '#ddd';
	});
}

// STAR RATING FUNCTION
stars.forEach(function(star) {
	star.addEventListener('click', function() {
		currentRating = parseInt(this.getAttribute('data-rating'));
		selectedRatingInput.value = currentRating;
		updateStars();
		updateRatingText();
		submitButton.disabled = false;
	});

	star.addEventListener('mouseenter', function() {
		var hoverRating = parseInt(this.getAttribute('data-rating'));
		highlightStars(hoverRating);
	});

	star.addEventListener('mouseleave', function() {
		updateStars();
	});
});

function updateStars() {
	stars.forEach(function(star, index) {
		if (index < currentRating) {
			star.classList.add('active');
			star.style.color = '#eedd40ff';
		} else {
			star.classList.remove('active');
			star.style.color = '#ddd';
		}
	});
}

function highlightStars(rating) {
	stars.forEach(function(star, index) {
		if (index < rating) {
			star.style.color = '#eedd40ff';
		} else {
			star.style.color = '#ddd';
		}
	});
}

function updateRatingText() {
	ratingText.textContent = ratingMessages[currentRating] || 'Click on stars to rate';
}
