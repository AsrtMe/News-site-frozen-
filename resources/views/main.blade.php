<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Форум</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
      @import url('https://fonts.googleapis.com/css2?family=Michroma&family=Tektur:wght@400..900&display=swap');
     
      body {
        margin: 0;
        padding: 0;
        height: 100vh;
        background: url("images/3.jpg") no-repeat center center fixed;
        background-size: cover;
        font-family: "Tektur", sans-serif;
        color: #e0e0e0;
      }

      body::before {
        content: "";
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: -1;
      }

      nav {
        background: #111 !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.6);
      }

      .box {
        height: 180px;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #232526, #414345);
        box-shadow: 0 4px 12px rgba(0,0,0,0.6);
      }

      .box h1 {
        font-size: 2.8rem;
        font-weight: bold;
        color: #fff;
        text-shadow: 2px 2px 10px rgba(0,0,0,0.8);
        letter-spacing: 2px;
      }

      .bix, .bix1 {
        max-width: 900px;
        width: 100%;
        margin: 40px auto;
        padding: 1.5rem;
        background: rgba(30,30,30,0.9);
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.6);
        animation: fadeIn 1s ease-in;
      }

      .post-content h2 {
        color: #66ccff;
        font-size: 1.6rem;
        margin-bottom: 1rem;
        border-left: 6px solid #3498db;
        padding-left: 0.6rem;
      }

      .post-content p {
        color: #ddd;
        line-height: 1.6;
      }

      .post-image img {
        max-width: 70%;
        height: auto;
        border: 3px solid #444;
        border-radius: 12px;
        box-shadow: 0 6px 14px rgba(0,0,0,0.6);
      }

      /* 🎨 Стили тегов */
      .tags {
        margin: 1rem 0;
      }
      .tag {
        display: inline-block;
        background: #3498db;
        color: #fff;
        padding: 5px 12px;
        margin: 4px 6px 0 0;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: 0.3s;
      }
      .tag:hover {
        background: #555;
      }

      .comments {
        margin-top: 1.5rem;
        background: #222;
        padding: 1rem;
        border-radius: 10px;
        box-shadow: inset 0 2px 6px rgba(0,0,0,0.4);
      }

      .comments h3 {
        color: #ccc;
        margin-bottom: 1rem;
      }

      .comments input[type="text"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 0.8rem;
        border-radius: 8px;
        border: 1px solid #444;
        background: #111;
        color: #fff;
      }

      /* 🔘 Новый стиль кнопок */
      button, .btn, .btn-link {
        background: linear-gradient(135deg, #1e1e2f, #2c2c44);
        color: #e0e0e0 !important;
        border: 1px solid #3a3a5c;
        padding: 8px 14px;
        border-radius: 8px;
        font-weight: 500;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 0 10px rgba(0, 150, 255, 0.3);
      }

      button:hover, .btn:hover, .btn-link:hover {
        background: linear-gradient(135deg, #2c2c44, #1e1e2f);
        box-shadow: 0 0 15px rgba(0, 150, 255, 0.6);
        color: #fff !important;
        transform: scale(1.05);
      }

      /* 📌 Кнопка "ещё" отдельный стиль */
      .readMoreBtn {
        background: transparent;
        border: none;
        color: #4da6ff !important;
        font-size: 1rem;
        box-shadow: none;
      }

      .readMoreBtn:hover {
        color: #66c2ff !important;
        text-decoration: underline;
        transform: none;
      }

      .comment-list p {
        background: #333;
        padding: 10px;
        border-radius: 5px;
        margin: 5px 0;
        color: #ddd;
      }

      #toTop {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #3498db;
        color: white;
        border: none;
        padding: 12px;
        border-radius: 50%;
        cursor: pointer;
        display: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.6);
      }

      footer {
        background: #111;
        color: white;
        padding-top: 45px;
        text-align: center;
        box-shadow: 0 -4px 10px rgba(0,0,0,0.6);
      }

      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
      }

       .logo {
      height: 80px;
      width: auto;
      margin-right: 12px;
      position: relative;
      top: -10px;
    }

  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a class="navbar-brand" href="main.html">
        <img src="images/logo.png" alt="Лого" class="logo"> BulBul
      </a>

      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="test-register">Регистрация</a></li>
          <li class="nav-item"><a class="nav-link" href="index.html">Главная</a></li>
          <li class="nav-item"><a class="nav-link" href="main.html">Посты</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Участники</a></li>
          <li class="nav-item"><a class="nav-link" href="about.html">О нас</a></li> 
        </ul>
      </div>
    </div>
  </nav>

  <div class="box">
    <h1>Форум</h1>
  </div>

  <div class="bix">
    <div class="post-content">
      <h2>Первый пост: Международный день мира! 🌍✌️</h2>
      <p>
        Друзья, сегодня, 21 сентября 2025 года, весь мир отмечает Международный день мира! 🌍✌️
        <span class="dots">...</span>
        <span class="moreText" style="display: none;">
          Этот праздник был учрежден ООН в 1981 году, чтобы напомнить о важности мира и ненасилия.
          В этот день во многих странах проходят акции, конференции и мероприятия, направленные на
          укрепление дружбы между народами и предотвращение конфликтов. 🕊️
        </span>
      </p>
      <button onclick="toggleText(this)" class="readMoreBtn btn btn-link p-0">ещё</button>
    </div>

    <div class="post-image">
      <img src="images/1.jpg" alt="Международный день мира">
    </div> 

    <div class="tags">
      <span class="tag">#Мир</span>
      <span class="tag">#ООН</span>
      <span class="tag">#21сентября</span>
    </div>

    <div class="comments">
      <h3>Комментарии</h3>
      <form>
        <input type="text" placeholder="Напишите ваш комментарий...">
        <button type="submit">Отправить</button>
      </form>
      <div class="comment-list">Комментарии: пока их нет, будьте первым!</div>
    </div>
  </div>


  <div class="bix1">
    <div class="post-content">
      <h2>Первый пост: Добро пожаловать на форум!</h2>
      <p>Это наш первый пост на форуме, созданный 19 сентября 2025 года...</p>
    </div>

    <div class="tags">
      <span class="tag">#форум</span>
      <span class="tag">#добро пожаловать</span>
    </div>

    <div class="comments">
      <h3>Комментарии</h3>
      <form>
        <input type="text" placeholder="Напишите ваш комментарий...">
        <button type="submit">Отправить</button>
      </form>
      <div class="comment-list">Комментарии: пока их нет, будьте первым!</div>
    </div>
  </div>

  <div class="bix">
    <div class="post-content">
      <h2>Привет друзья!!!</h2>
      <p>
        Это было в ночь на 8 августа, тогда у меня было день рождение.
        <span class="dots">...</span>
        <span class="moreText" style="display: none;">
          В этот день мои любимки пришли поздравить меня с днем рождения, я на самом деле был очень рад. 
          Для меня это было очень не ожиданно, радости моей не было предела, я люблю своих друзей ❤️❤️❤️!
        </span>
      </p>
      <button onclick="toggleText(this)" class="readMoreBtn btn btn-link p-0">ещё</button>
    </div>

    <div class="post-image">
      <img src="images/032.jpg" alt="Фото с друзьями">
    </div> 

    <div class="tags">
      <span class="tag">#Друзья</span>
      <span class="tag">#Праздник</span>
      <span class="tag">#ДР</span>
    </div>

    <div class="comments">
      <h3>Комментарии</h3>
      <form>
        <input type="text" placeholder="Напишите ваш комментарий...">
        <button type="submit">Отправить</button>
      </form>
      <div class="comment-list">Комментарии: пока их нет, будьте первым!</div>
    </div>
  </div>

  <div class="bix">
    <div class="post-content">
      <h2>Привет друзья!!!</h2>
      <p>
        Познакомьтесь с моим лучшим другом Диасом! 😎 
        <span class="dots">...</span>
        <span class="moreText" style="display: none;">
         Этот парень — настоящий красавчик, и я не просто про внешность! Диас — это человек, который всегда знает, как поднять настроение, зарядить всех вокруг позитивом и сделать любой день ярче. 🌟 Его харизма просто зашкаливает, а улыбка — это отдельный вид искусства! 😄
        Диас — это тот, кто всегда поддержит в трудную минуту, даст дельный совет и с легкостью разведет тучи над головой. Он обожает приключения, будь то спонтанная поездка за город или просто веселый вечер в компании друзей. 🎉 Музыка, юмор и хорошая компания — его стихия! А еще он мастер находить что-то крутое в самых обычных вещах. 🚀
        Если вы встретите Диаса, готовьтесь к тому, что он заразит вас своим энтузиазмом и энергией! 💥 Горжусь, что у меня есть такой друг, и хочу, чтобы все знали, какой он классный! #ДиасКрасавчик #ЛучшийДруг #ПозитивНаМаксимум
        </span>
      </p>
      <button onclick="toggleText(this)" class="readMoreBtn btn btn-link p-0">ещё</button>
    </div>

    <div class="post-image">
      <img src="images/d.jpg" alt="Фото с друзьями">
    </div> 

    <div class="tags">
      <span class="tag">#ДиасКрасавчик</span>
      <span class="tag">#ЛучшийДруг</span>
      <span class="tag">#ПозитивНаМаксимум</span>
    </div>

    <div class="comments">
      <h3>Комментарии</h3>
      <form>
        <input type="text" placeholder="Напишите ваш комментарий...">
        <button type="submit">Отправить</button>
      </form>
      <div class="comment-list">Комментарии: пока их нет, будьте первым!</div>
    </div>
  </div>

  <button id="toTop">⬆</button>

  <footer>
    <p>© 2025 Мой форум | Сделано с ❤️</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    const toTop = document.getElementById("toTop");
    window.addEventListener("scroll", () => {
      toTop.style.display = window.scrollY > 200 ? "block" : "none";
    });
    toTop.addEventListener("click", () => {
      window.scrollTo({ top: 0, behavior: "smooth" });
    });

    function toggleText(button) {
      const parent = button.closest(".post-content");
      const dots = parent.querySelector(".dots");
      const moreText = parent.querySelector(".moreText");

      if (dots.style.display === "none") {
        dots.style.display = "inline";
        moreText.style.display = "none";
        button.innerHTML = "ещё";
      } else {
        dots.style.display = "none";
        moreText.style.display = "inline";
        button.innerHTML = "свернуть";
      }
    }
  </script>
</body>
</html>