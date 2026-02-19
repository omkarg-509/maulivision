/* ================= ELEMENTS ================= */

const form = document.getElementById("askForm");

const emailInput = document.getElementById("userEmail");
const questionInput = document.getElementById("userQuestion");

const successBox = document.getElementById("successBox");


/* ================= SUBMIT ================= */

form.addEventListener("submit", function(e){

  e.preventDefault();


  const email = emailInput.value.trim();
  const question = questionInput.value.trim();


  if(email === "" || question === ""){
    alert("Please fill all required fields.");
    return;
  }


  /* Build Message */

  const subject = "SciEdIn Question Submission";

  const body = `
User Email: ${email}

Question:

${question}
`;


  /* Gmail Compose Link */

  const gmailURL =
    "https://mail.google.com/mail/?view=cm&fs=1" +
    "&to=himanshubansal7373@gmail.com" +
    "&su=" + encodeURIComponent(subject) +
    "&body=" + encodeURIComponent(body);


  /* Open Gmail */

  window.open(gmailURL, "_blank");


  /* Show Success Message */

  form.style.display = "none";

  successBox.style.display = "block";

});