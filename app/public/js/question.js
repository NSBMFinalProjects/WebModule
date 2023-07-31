const delay = (ms) => new Promise((res) => setTimeout(res, ms));

const btn = document.getElementById("submit");
const setBtn = (text, color) => {
  btn.style.backgroundColor = color;
  btn.innerText = text;
};

document
  .getElementsByTagName("form")[0]
  .addEventListener("submit", async function (event) {
    event.preventDefault();
    btn.disabled = true;

    const formData = new FormData(event.target);
    const selectedAnswer = formData.get("answer");

    const res = await fetch("/api/question/answer", {
      method: "POST",
      body: JSON.stringify({
        answer: selectedAnswer,
      }),
    });
    if (res.status === 200) {
      const data = await res.json();
      const isCorrect = data.is_correct;
      if (isCorrect) {
        setBtn("Your answer is correct", "#05ce91");
        await delay(3000);

        window.location.href = "/leaderboard";
      } else {
        setBtn("Your answer is wrong", "red");
      }

      return;
    }

    setBtn("Something went wrong", "red");
    btn.disabled = false;
    return;
  });
