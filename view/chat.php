<?php include 'templates/header.php';

    // GET요청은 허용하지 않음.
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        header("Location: /");
        exit;
    }

    $mode = 'CREATE';
    if (isset($_POST['uuid'])) {
        $mode = 'ENTER';
    }
?>

    <section id="chat-section">
        <div id="chat-wrap">
            <div id="chat-content"></div>
            <div id="chat-input-wrap">
                <textarea id="chat-input" placeholder="채팅을 입력해주세요.."></textarea>
                <img id="chat-send-btn" onclick="sendMessage()" src="assets/images/send-chat-btn.png">
            </div>
        </div>

        <div id="participant-wrap">
            <div id="participant-title">
                참가자 목록
            </div>
            <div id="participant-content"></div>
        </div>
    </section>
    <div id="chat-exit-btn" onclick="window.location.href = '/'">나가기</div>

    <script>

        const socket = new WebSocket(WEB_SOCKET_SERVER + "/chat/message");
        const mode = '<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>';
        const nickname = '<?= htmlspecialchars($_POST['nickname'], ENT_QUOTES, 'UTF-8') ?>';

        // DOM 객체
        const participantContentEle = $('#participant-content');
        const chatContentEle = $('#chat-content');
        const chatInput = $('#chat-input');

        // 웹소켓이 연결되면, 최초 실행.
        socket.onopen = function() {

            if (mode === 'CREATE') { // 채팅방 신규 생성
                const uuid = crypto.randomUUID();
                const title = '<?= htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>';

                socket.send(
                    JSON.stringify({
                        type: 'CREATE',
                        uuid: uuid,
                        title: title,
                        nickname: nickname
                    })
                );

            } else { // 채팅방 입장

                const uuid = '<?= htmlspecialchars($_POST['uuid'] ?? '', ENT_QUOTES, 'UTF-8') ?>';

                socket.send(
                    JSON.stringify({
                        type: 'ENTER',
                        uuid: uuid,
                        nickname: nickname
                    })
                );

            }

        }


        // 웹소켓 이벤트.
        socket.onmessage = function (event) {
            const data = JSON.parse(event.data);
            const type = data.type;

            switch (type) {
                case 'NICKNAME':
                    participantContentEle.empty();

                    const nicknames = data.nicknames;

                    // 본인 닉네임 제거
                    const index = nicknames.indexOf(nickname);
                    if (index !== -1) {
                        nicknames.splice(index, 1);
                    }

                    const participantCardEle = $('<div>').addClass('participant-card me').text(nickname);
                    participantContentEle.append(participantCardEle);

                    // 다른 사람 닉네임 순회
                    for (const otherNickname of nicknames) {
                        const participantCardEle = $('<div>').addClass('participant-card other').text(otherNickname);
                        participantContentEle.append(participantCardEle);
                    }

                    break;
                case 'MESSAGE':
                    const chatCardWrap = $('<div>').addClass('chat-card-wrap');
                    const nicknameEle = $('<h4>').addClass('chat-nickname other').text(data.from);
                    const chatCardEle = $('<div>').addClass('chat-card other').text(data.message);

                    chatCardWrap.append(nicknameEle, chatCardEle);
                    chatContentEle.append(chatCardWrap);

                    // 하단 이동
                    chatContentEle.scrollTop(chatContentEle[0].scrollHeight);

                    break;
                case 'ERROR':
                    alert(data['error_message']);
                    break;
            }

        }

        // 메세지 전송 관련 함수 및 이벤트
        function sendMessage() {
            const chatText = chatInput.val();

            const chatCardWrap = $('<div>').addClass('chat-card-wrap');
            const nicknameEle = $('<h4>').addClass('chat-nickname me').text(nickname);
            const chatCardEle = $('<div>').addClass('chat-card me').text(chatText);

            chatCardWrap.append(nicknameEle, chatCardEle);
            chatContentEle.append(chatCardWrap);

            socket.send(
                JSON.stringify(
                    {
                        'type': "SEND_MESSAGE",
                        'message': chatText
                    }
                )
            );

            // 하단 이동
            chatContentEle.scrollTop(chatContentEle[0].scrollHeight);
        }

        chatInput.on('keydown', function(event) {
            if (chatInput.val().trim() === '') {
                event.preventDefault();
                return;
            }
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                sendMessage();
                chatInput.val('');
            }
        });


    </script>

<?php include 'templates/footer.php'; ?>