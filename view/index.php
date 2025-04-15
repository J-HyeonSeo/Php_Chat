<?php include 'templates/header.php'; ?>

  <div id="back-drop"></div>

  <!-- 신규 채팅방 생성 모달달 -->
  <div class="modal" id="new-chat-modal">
    <h3 class="modal-title">새 채팅</h3>
    <div class="modal-input-wrap">
      <span class="modal-close-btn">X</span>
      <label for="chat-room-title">채팅방 제목</label>
      <br>
      <input id="chat-room-title" placeholder="제목을 입력해주세요...">
      <br>
      <label for="nickname">닉네임</label>
      <br>
      <input id="nickname" placeholder="닉네임을 입력해주세요...">
    </div>
    <div id="create-chat-room-btn" class="modal-btn">완료</div>
  </div>

  <!-- 방 입장 모달 -->
  <div class="modal" id="chat-enter-modal">
    <h3 class="modal-title">방 입장</h3>
    <div class="modal-input-wrap">
      <span class="modal-close-btn">X</span>
      <label for="nickname">닉네임</label>
      <br>
      <input id="nickname" placeholder="닉네임을 입력해주세요...">
    </div>
    <div id="create-chat-room-btn" class="modal-btn">완료</div>
  </div>

  <div id="top-wrap">
    <span style="width: 130px;"></span>
    <h2 id="top-title">채팅방 목록</h2>
    <div id="new-chat-btn">새 채팅..</div>
  </div>

  <div id="chat-room-wrap">
    <div class="chat-room-card">
      <h3 class="chat-title">심심해요... PHP로 대화할래요?</h3>
      <h3 class="chat-count">1 / 4</h3>
    </div>
    <div class="chat-room-card">
      <h3 class="chat-title">심심해요... PHP로 대화할래요?</h3>
      <h3 class="chat-count">1 / 4</h3>
    </div>
    <div class="chat-room-card">
      <h3 class="chat-title">심심해요... PHP로 대화할래요?</h3>
      <h3 class="chat-count">1 / 4</h3>
    </div>
    <div class="chat-room-card">
      <h3 class="chat-title">심심해요... PHP로 대화할래요?</h3>
      <h3 class="chat-count">1 / 4</h3>
    </div>
    <div class="chat-room-card">
      <h3 class="chat-title">심심해요... PHP로 대화할래요?</h3>
      <h3 class="chat-count">1 / 4</h3>
    </div>
    <div class="chat-room-card">
      <h3 class="chat-title">심심해요... PHP로 대화할래요?</h3>
      <h3 class="chat-count">1 / 4</h3>
    </div>
    <div class="chat-room-card">
      <h3 class="chat-title">심심해요... PHP로 대화할래요?</h3>
      <h3 class="chat-count">1 / 4</h3>
    </div>
    <div class="chat-room-card">
      <h3 class="chat-title">심심해요... PHP로 대화할래요?</h3>
      <h3 class="chat-count">1 / 4</h3>
    </div>
  </div>

  <sciprt>
    <!-- const socket = new WebSocket(); -->
  </sciprt>

<?php include 'templates/footer.php'; ?>
