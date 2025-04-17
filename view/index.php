<?php include 'templates/header.php'; ?>

<div id="back-drop" onclick="closeModal()"></div>

<!-- 신규 채팅방 생성 모달 -->
<div class="modal" id="new-chat-modal">
    <h3 class="modal-title">새 채팅</h3>
    <form action="chat.php" method="POST">
        <div class="modal-input-wrap">
            <span class="modal-close-btn" onclick="closeModal()">X</span>
            <label for="chat-room-title">채팅방 제목</label>
            <br>
            <input name="title" id="chat-room-title" required minlength="2" maxlength="20" placeholder="제목을 입력해주세요...">
            <br>
            <label for="nickname">닉네임</label>
            <br>
            <input name="nickname" id="nickname" required minlength="2" maxlength=7" placeholder="닉네임을 입력해주세요...">
        </div>
        <div class="modal-btn-wrap">
            <button id="create-chat-room-btn" class="modal-btn">완료</button>
        </div>
    </form>
</div>

<!-- 방 입장 모달 -->
<div class="modal" id="chat-enter-modal">
    <h3 class="modal-title">방 입장</h3>
    <form action="chat.php" method="POST">
        <div class="modal-input-wrap">
            <input hidden="hidden" name="uuid" id="uuid-input">
            <span class="modal-close-btn" onclick="closeModal()">X</span>
            <label for="nickname">닉네임</label>
            <br>
            <input name="nickname" id="nickname" required minlength="2" maxlength=7" placeholder="닉네임을 입력해주세요...">
        </div>
        <div class="modal-btn-wrap">
            <button id="create-chat-room-btn" class="modal-btn">완료</button>
        </div>
    </form>
</div>

<div id="top-wrap">
    <span style="width: 130px;"></span>
    <h2 id="top-title">채팅방 목록</h2>
    <div id="new-chat-btn" onclick="openNewChatModal()">새 채팅..</div>
</div>

<div id="chat-room-wrap"></div>

<script>

    // 채팅방 목록을 가져오기 위한 웹소켓 서버 연결.
    const socket = new WebSocket(WEB_SOCKET_SERVER + "/chat/room");

    // DOM 객체 변수.
    const chatRoomWrapEle = $('#chat-room-wrap');
    const backDropEle = $('#back-drop');
    const newChatModalEle = $('#new-chat-modal');
    const chatEnterModalEle = $('#chat-enter-modal');
    const uuidInputEle = $('#uuid-input');


    // 채팅방 목록을 전달받았을 경우.
    socket.onmessage = function (event) {
        const chatRooms = JSON.parse(event.data).chatRooms;

        // 채팅방 목록 비우기
        chatRoomWrapEle.empty();

        for (chatRoom of chatRooms) {

            const chatRoomCardEle = $('<div>').addClass('chat-room-card')
                .attr('data-uuid', chatRoom.uuid)
                .on('click', openEnterModal);

            const chatTitleEle = $('<h3>').addClass('chat-title')
                .text(chatRoom.title);

            const chatCountEle = $('<h3>').addClass('chat-count')
                .text(`${chatRoom.count} / 4`);

            chatRoomCardEle.append(chatTitleEle, chatCountEle);

            chatRoomWrapEle.append(chatRoomCardEle);
        }

    }


    function openNewChatModal() {
        backDropEle.show();
        newChatModalEle.show();
    }

    function openEnterModal(event) {

        // input에 uuid 할당하기.
        uuidInputEle.val(event.target.dataset.uuid);

        backDropEle.show();
        chatEnterModalEle.show();
    }

    function closeModal() {
        const modal = $('.modal');
        modal.hide();
        backDropEle.hide();
    }

</script>

<?php include 'templates/footer.php'; ?>
