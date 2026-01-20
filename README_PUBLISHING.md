# 퍼블리싱 작업 가이드

---

## 🚀 처음 시작하기 (최초 1회만)

### 1단계: Laragon 설치

1. **다운로드**: https://laragon.org/download/
2. **"Laragon Full"** 버전 다운로드 (필수!)
3. 설치 파일 실행 → 기본 설정으로 설치
4. 설치 완료

### 2단계: PHP 버전 확인

1. Laragon 실행
2. 왼쪽 상단 **Menu** → **PHP** → **Version**
3. **PHP 8.3.x** 선택 (기본 포함)

### 3단계: 프로젝트 배치

```
이 프로젝트 폴더 전체를 복사
→ C:\laragon\www\ 안에 붙여넣기
→ 결과: C:\laragon\www\publishing\
```

### 4단계: 프로젝트 실행

1. Laragon에서 **"Start All"** 버튼 클릭
2. **`start.bat`** 더블클릭
3. 처음 실행 시 자동으로 설정 완료 (패키지 설치, 키 생성 등)
4. 브라우저 자동 실행

**완료! 이제 작업 가능합니다.** 🎉

---

## 💻 매일 작업 시작하기

```
1. Laragon 실행
2. "Start All" 버튼 클릭
3. start.bat 더블클릭
4. 브라우저 자동 실행 → 작업 시작!
```

**또는**: 브라우저에서 `http://localhost:8000` 직접 접속

---

## 📁 작업할 파일 위치

| 작업 내용 | 경로 |
|----------|------|
| **HTML 파일** | `resources/views/home/` |
| **CSS 파일** | `public/css/` |
| **JavaScript** | `public/js/` |
| **이미지** | `public/images/` |

**파일 수정 → 브라우저에서 F5 (새로고침) → 즉시 확인!**

---

## ⚠️ 주의사항

### ✅ 가능한 작업
- 사용자 페이지 HTML/CSS/JS 수정
- 이미지 추가/변경

## 🔧 문제 해결

### Laragon이 시작 안 돼요

**해결**:
1. XAMPP, WAMP 등 다른 웹서버 프로그램 종료
2. Laragon 재시작
3. 안 되면 PC 재시작

### start.bat 실행 시 오류

**"PHP 8.3 or 8.4 required" 경고**:
- Menu → PHP → Version → PHP 8.3 선택
- Laragon 재시작
- start.bat 다시 실행

**"Composer install failed" 오류**:
- 인터넷 연결 확인
- Laragon Terminal 열기 → `composer clear-cache` 실행
- start.bat 다시 실행

### 페이지가 안 열려요

**해결**:
1. Laragon에서 "Start All" 버튼 눌렀는지 확인
2. `http://localhost:8000` 또는 `http://publishing.test` 접속
3. 안 되면 start.bat 다시 실행

### CSS/JS 변경이 안 보여요

**해결**:
- **Ctrl + F5** (강력 새로고침)
- 브라우저 개발자 도구(F12) → Network 탭 → "Disable cache" 체크

---

## 🎯 Laragon 버튼 설명

| 버튼 | 기능 |
|------|------|
| **Start All** | 서버 시작 (매일 작업 시작할 때 클릭) |
| **Stop All** | 서버 중지 (작업 종료할 때) |
| **Terminal** | 명령어 입력 (고급) |
| **Root** | 웹 폴더 열기 (`C:\laragon\www`) |

---

## 📚 간단 요약

### 처음 한 번
```
Laragon 설치 → 프로젝트 복사 → start.bat 실행
```

### 매일 작업
```
Laragon Start All → start.bat → 작업 시작
```

### 작업 방법
```
파일 수정 → F5 새로고침 → 확인
```
