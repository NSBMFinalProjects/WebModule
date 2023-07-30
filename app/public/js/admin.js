document
  .getElementsByTagName("form")[0]
  .addEventListener("submit", async function (event) {
    event.preventDefault();
    const data = new FormData(event.target);

    const title = data.get("title");
    const question = data.get("question");
    const a1 = data.get("a1");
    const a2 = data.get("a2");
    const a3 = data.get("a3");
    const a4 = data.get("a4");
    const correctAnswer = data.get("Answer");

    if (
      title === "" ||
      question === "" ||
      a1 === "" ||
      a2 === "" ||
      a3 === "" ||
      a4 === "" ||
      correctAnswer === ""
    ) {
      return;
    }

    const res = await fetch("/api/question/create", {
      method: "POST",
      body: JSON.stringify({
        title: title,
        question: question,
        1: a1,
        2: a2,
        3: a3,
        4: a4,
        answer: correctAnswer,
      }),
    });

    if (res.status !== 200) {
      alert("Error creating the question");
      return;
    }

    alert("Created the question successfully");
    return;
  });
