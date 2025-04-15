<?php include 'templates/header.php'; ?>

  <section id="chat-section">
    <div id="chat-wrap">
      <div id="chat-content">
        <div class="chat-card-wrap">
          <h4 class="chat-nickname me">ME</h4>
          <div class="chat-card me">루비짱</div>
        </div>

        <div class="chat-card-wrap">
          <h4 class="chat-nickname other">루비</h4>
          <div class="chat-card other">하이~</div>
        </div>

        <div class="chat-card-wrap">
          <h4 class="chat-nickname me">ME</h4>
          <div class="chat-card me">나니가스키?</div>
        </div>

        <div class="chat-card-wrap">
          <h4 class="chat-nickname other">루비</h4>
          <div class="chat-card other">쵸코민토 요리모 아! 나! 타!</div>
        </div>

        <div class="chat-card-wrap">
          <h4 class="chat-nickname me">ME</h4>
          <div class="chat-card me">아유무짱</div>
        </div>

        <div class="chat-card-wrap">
          <h4 class="chat-nickname other">아유무</h4>
          <div class="chat-card other">하이~</div>
        </div>

        <div class="chat-card-wrap">
          <h4 class="chat-nickname me">ME</h4>
          <div class="chat-card me">나니가스키?</div>
        </div>

        <div class="chat-card-wrap">
          <h4 class="chat-nickname other">아유무</h4>
          <div class="chat-card other">스토로베리 후레이바 요리모 아! 나! 타!</div>
        </div>

      </div>
      <div id="chat-input-wrap">
        <textarea id="chat-input" placeholder="채팅을 입력해주세요.."></textarea>
        <img id="chat-send-btn" src="assets/images/send-chat-btn.png">
      </div>
    </div>

    <div id="participant-wrap">
      <div id="participant-title">
        참가자 목록
      </div>
      <div id="participant-content">
        <div class="participant-card me">MR</div>
        <div class="participant-card other">루비</div>
        <div class="participant-card other">아유무</div>
        <div class="participant-card other">시키</div>
      </div>
    </div>
  </section>
  <div id="chat-exit-btn">나가기</div>
  
<?php include 'templates/footer.php'; ?>