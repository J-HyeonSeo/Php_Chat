<?php include 'templates/header.php';
    $mode = 'CREATE';
    if (isset($_POST['uuid'])) {
        $mode = 'ENTER';
    }
?>

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
            </div>
        </div>
    </section>
    <div id="chat-exit-btn" onclick="window.location.href = '/'">나가기</div>

    <script>

        const socket = new WebSocket(WEB_SOCKET_SERVER + "/chat/message");
        const mode = '<?= htmlspecialchars($mode, ENT_QUOTES, 'UTF-8') ?>';
        const nickname = '<?= htmlspecialchars($_POST['nickname'], ENT_QUOTES, 'UTF-8') ?>';

        // 웹소켓이 연결되면, 최초 실행.
        socket.onopen = function() {

            if (mode === 'CREATE') { // 채팅방 신규 생성
                const uuid = crypto.randomUUID();
                const title = '<?= htmlspecialchars($_POST['title'], ENT_QUOTES, 'UTF-8') ?>';

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
                    // TODO => 닉네임 리스트 갱신.
                    break;
                case 'MESSAGE':
                    // TODO => 메세지 DOM 추가.
                    break;
                case 'ERROR':
                    alert(data['error_message']);
                    break;
            }

        }


    </script>

<?php include 'templates/footer.php'; ?>