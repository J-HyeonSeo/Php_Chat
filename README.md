# PHP CHAT

RatChet 라이브러리 기반으로, WebSocket를 사용하여 구현한 그룹채팅 서비스입니다.

PHP 언어로 구현되어있으며, 일회성 채팅방을 만들고, 채팅방에 있는 사람들끼리 메세지를 주고받을 수 있습니다.

---

# 프로젝트 실행 방법

- git repository 클론 하기.
```shell
git clone https://github.com/J-HyeonSeo/Php_Chat.git
```

- 클론된 repository로 이동하기.
```shell
cd Php_Chat
```

- 도커 빌드 하기
```shell
docker build -t phpchat .
```

- 도커 실행하기
```shell
docker run -d --name phpchat -p 7777:80 -p 7778:7778 phpchat
```

- 서버 접근하기
```
http://localhost:7777
```

# 시연

- 메인화면
![main](/doc/main.png)

- 채팅방 만들기
![create](/doc/create.png)

- 방 입장하기
![enter](/doc/enter.png)

- 채팅창
![chat](/doc/chat.png)