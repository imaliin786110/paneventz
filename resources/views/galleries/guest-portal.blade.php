@extends('layouts.app')

@section('title', $album->title . ' – Paneventz Client & Guest Portal')
@section('description', 'Browse, watch, and download your high-resolution wedding photos and cinematic films by Paneventz.')

@section('content')
<div style="background: #090b10; color: #fff; min-height: 100vh; padding-bottom: 120px;">

    <!-- TOP VIP NAVIGATION BAR -->
    <nav style="position: relative; background: rgba(10, 14, 24, 0.95); border-bottom: 1px solid rgba(0, 240, 255, 0.15); padding: 18px 8%; display: flex; justify-content: space-between; align-items: center; backdrop-filter: blur(16px);">
        <div class="logo">
            <a href="/" style="color: #fff; text-decoration: none; font-family: Georgia, serif; font-size: 1.3rem; letter-spacing: 1px;">Paneventz</a>
        </div>
        <div style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #c4a472; display: flex; align-items: center; gap: 8px;">
            <span style="display: inline-block; width: 8px; height: 8px; background: #00ff9d; border-radius: 50%; box-shadow: 0 0 8px #00ff9d;"></span>
            PRIVATE CLIENT PORTAL
        </div>
    </nav>

    @if($needsPin)
        <!-- PRIVATE PIN VERIFICATION VIEW -->
        <div style="max-width: 460px; margin: 100px auto; background: rgba(18, 25, 41, 0.95); border: 1px solid rgba(0, 240, 255, 0.25); box-shadow: 0 20px 50px rgba(0,0,0,0.8), 0 0 20px rgba(0,240,255,0.06); padding: 45px 35px; text-align: center; border-radius: 12px;">
            <div style="width: 64px; height: 64px; margin: 0 auto 20px; border-radius: 50%; background: rgba(0,240,255,0.1); border: 1px solid rgba(0,240,255,0.3); display: flex; align-items: center; justify-content: center; font-size: 26px;">
                🔒
            </div>
            <span style="color: #c4a472; font-size: 11px; letter-spacing: 3px; text-transform: uppercase; font-weight: 600;">PRIVATE WEDDING COLLECTION</span>
            <h2 style="font-family: Georgia, serif; font-size: 26px; font-weight: normal; margin: 12px 0 10px; color: #fff;">
                Enter Access Passcode
            </h2>
            <p style="color: #94a3b8; font-size: 13.5px; line-height: 1.6; margin-bottom: 25px;">
                This wedding gallery is private. Please enter the 4-digit passcode provided by {{ $album->couple_names ?: 'the couple' }}.
            </p>

            <form onsubmit="submitPin(event)">
                <input 
                    type="password" 
                    id="pinInput" 
                    maxlength="10" 
                    placeholder="••••" 
                    class="form-control" 
                    style="text-align: center; font-size: 26px; letter-spacing: 12px; margin-bottom: 16px; background: #070a12; border: 1px solid rgba(0,240,255,0.3); color: #fff; border-radius: 8px; padding: 12px;" 
                    required 
                    autofocus
                >
                <div id="pinError" style="color: #ff007f; font-size: 13px; margin-bottom: 14px; display: none;"></div>
                <button type="submit" id="pinBtn" class="film-button" style="width: 100%; background: linear-gradient(135deg, #0099cc, #00f0ff); color: #070a12; font-weight: 700; border: none; padding: 14px; border-radius: 8px; font-size: 13px; letter-spacing: 1.5px; text-transform: uppercase; cursor: pointer; box-shadow: 0 0 20px rgba(0,240,255,0.35);">
                    Unlock Collection →
                </button>
            </form>
        </div>
    @else
        @php
            $photoList = $photos->where('is_video', false);
            $videoList = $photos->where('is_video', true);
        @endphp

        <!-- GALLERY HERO HEADER -->
        <section style="text-align: center; padding: 60px 8% 30px; background: linear-gradient(180deg, rgba(14, 20, 34, 0.8) 0%, rgba(9, 11, 16, 1) 100%);">
            <span style="color: #c4a472; font-size: 11px; letter-spacing: 3px; text-transform: uppercase; font-weight: 600;">THE WEDDING HEIRLOOM COLLECTION</span>
            <h1 style="font-family: 'Cormorant Garamond', Georgia, serif; font-size: clamp(32px, 5.5vw, 68px); font-weight: normal; margin: 15px 0 10px; color: #fff; letter-spacing: -0.02em;">
                {{ $album->title }}
            </h1>
            <p style="color: #94a3b8; font-size: 14.5px; max-width: 700px; margin: 0 auto 30px;">
                📍 {{ $album->location ?: 'Bespoke Destination' }} · 📅 {{ $album->event_date ? $album->event_date->format('d F Y') : 'Celebration' }} · 📸 {{ $photoList->count() }} Photos @if($videoList->count() > 0) · 🎬 {{ $videoList->count() }} Cinema Films @endif
            </p>

            <!-- TABS & FILTER CONTROLS -->
            <div style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap; margin-top: 20px;">
                <button 
                    type="button" 
                    id="filterAllBtn" 
                    onclick="setMediaFilter('all')" 
                    class="media-tab-btn active"
                    style="padding: 10px 24px; border-radius: 30px; font-size: 12px; letter-spacing: 1.5px; text-transform: uppercase; cursor: pointer; transition: all 0.25s;">
                    All Media ({{ $photos->count() }})
                </button>

                @if($photoList->count() > 0)
                    <button 
                        type="button" 
                        id="filterPhotosBtn" 
                        onclick="setMediaFilter('photos')" 
                        class="media-tab-btn"
                        style="padding: 10px 24px; border-radius: 30px; font-size: 12px; letter-spacing: 1.5px; text-transform: uppercase; cursor: pointer; transition: all 0.25s;">
                        Photos ({{ $photoList->count() }})
                    </button>
                @endif

                @if($videoList->count() > 0)
                    <button 
                        type="button" 
                        id="filterVideosBtn" 
                        onclick="setMediaFilter('videos')" 
                        class="media-tab-btn"
                        style="padding: 10px 24px; border-radius: 30px; font-size: 12px; letter-spacing: 1.5px; text-transform: uppercase; cursor: pointer; transition: all 0.25s;">
                        🎬 Cinema Videos ({{ $videoList->count() }})
                    </button>
                @endif

                <button 
                    type="button" 
                    id="tabSelfieBtn" 
                    onclick="toggleSelfieModal()" 
                    style="background: transparent; color: #c4a472; border: 1px solid #c4a472; padding: 10px 24px; border-radius: 30px; font-size: 12px; letter-spacing: 1.5px; text-transform: uppercase; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.25s;">
                    <span>✨</span> Find My Photos (AI)
                </button>
            </div>
        </section>

        <!-- MASTER MEDIA GRID -->
        <div id="mediaGridSection" style="padding: 20px 8%; max-width: 1440px; margin: 0 auto;">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 20px;">
                @foreach($photos as $p)
                    <div class="media-card-item {{ $p->is_video ? 'type-video' : 'type-photo' }}" 
                         style="background: #111726; border-radius: 8px; overflow: hidden; position: relative; aspect-ratio: 1; border: 1px solid rgba(0, 240, 255, 0.12); box-shadow: 0 8px 24px rgba(0,0,0,0.5); transition: transform 0.3s, border-color 0.3s;">
                        
                        <!-- MEDIA THUMBNAIL / PREVIEW -->
                        <div style="width: 100%; height: 100%; cursor: pointer;" 
                             onclick="openMediaPreview('{{ $p->full_url }}', '{{ $p->direct_download_url }}', {{ $p->is_video ? 'true' : 'false' }}, '{{ addslashes($p->file_name ?? '') }}')">
                            
                            @if($p->is_video)
                                <div style="width: 100%; height: 100%; position: relative; background: #060911; display: flex; align-items: center; justify-content: center;">
                                    @if($p->thumbnail_url && !str_contains($p->thumbnail_url, 'uc?export=download'))
                                        <img src="{{ $p->thumbnail_url }}" alt="{{ $p->file_name }}" loading="lazy" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.7;">
                                    @else
                                        <div style="font-size: 40px; opacity: 0.6;">🎬</div>
                                    @endif
                                    <div style="position: absolute; width: 56px; height: 56px; border-radius: 50%; background: rgba(0, 240, 255, 0.25); border: 2px solid #00f0ff; display: flex; align-items: center; justify-content: center; box-shadow: 0 0 20px rgba(0,240,255,0.5); color: #fff; font-size: 20px;">
                                        ▶
                                    </div>
                                    <span style="position: absolute; top: 12px; left: 12px; background: rgba(0,0,0,0.8); border: 1px solid rgba(0,240,255,0.4); color: #00f0ff; font-size: 10px; letter-spacing: 1.5px; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase;">
                                        CINEMA @if($p->file_size) · {{ $p->file_size }} @endif
                                    </span>
                                </div>
                            @else
                                <img 
                                    src="{{ $p->thumbnail_url ?: $p->full_url }}" 
                                    alt="Wedding photo by Paneventz" 
                                    loading="lazy" 
                                    style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.4s;"
                                    onmouseover="this.style.transform='scale(1.05)'"
                                    onmouseout="this.style.transform='scale(1)'"
                                >
                            @endif
                        </div>

                        <!-- 1-CLICK DIRECT WEBSITE DOWNLOAD BUTTON -->
                        <div style="position: absolute; bottom: 10px; right: 10px; z-index: 2;">
                            <a href="{{ $p->direct_download_url }}" 
                               title="Download directly from Paneventz"
                               style="background: rgba(10, 14, 24, 0.85); backdrop-filter: blur(8px); border: 1px solid rgba(0, 240, 255, 0.35); color: #00f0ff; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 600; text-decoration: none; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.6); transition: all 0.2s;">
                                <span>↓</span> Download
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- AI SELFIE SCANNER MODAL (FOR GUESTS) -->
        <div id="selfieModal" class="modal-overlay" onclick="closeSelfieOnBackdrop(event)">
            <div style="max-width: 600px; width: 90%; background: #101626; border: 1px solid rgba(0, 240, 255, 0.3); border-radius: 12px; padding: 35px 30px; text-align: center; position: relative; box-shadow: 0 25px 60px rgba(0,0,0,0.9);">
                <button type="button" class="modal-close" onclick="toggleSelfieModal()">&times;</button>
                
                <span style="color: #c4a472; font-size: 11px; letter-spacing: 2px; text-transform: uppercase; font-weight: 600;">FACIAL RECOGNITION AI</span>
                <h3 style="font-family: Georgia, serif; font-size: 24px; font-weight: normal; margin: 10px 0 8px; color: #fff;">
                    Find Every Photo You Appear In
                </h3>
                <p style="color: #94a3b8; font-size: 13px; margin-bottom: 20px;">
                    Take a quick selfie or upload a portrait. Our AI will scan all {{ $photoList->count() }} photos and find yours in seconds!
                </p>

                <!-- VIDEO WEBCAM FEED -->
                <div id="cameraWrapper" style="display: none; max-width: 320px; margin: 0 auto 20px; position: relative; border-radius: 12px; overflow: hidden; border: 2px solid #00f0ff;">
                    <video id="webcamVideo" autoplay playsinline muted style="width: 100%; aspect-ratio: 3/4; object-fit: cover; transform: scaleX(-1);"></video>
                </div>
                <canvas id="selfieCanvas" style="display: none;"></canvas>

                <!-- CAMERA CONTROLS -->
                <div style="display: flex; justify-content: center; gap: 10px; flex-wrap: wrap;">
                    <button type="button" id="startCamBtn" onclick="startWebcam()" class="film-button" style="background: linear-gradient(135deg, #0099cc, #00f0ff); color: #070a12; font-weight: 700; border: none;">
                        Open Camera 📸
                    </button>
                    <button type="button" id="snapBtn" onclick="captureSelfie()" class="film-button" style="display: none; background: #00ff9d; color: #070a12; font-weight: 700; border: none;">
                        Snap & Search 🔍
                    </button>
                    <label class="film-button" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.2); color: #fff; cursor: pointer;">
                        Upload Photo 📁
                        <input type="file" id="fileSelfieInput" accept="image/*" onchange="handleFileSelfie(event)" style="display: none;">
                    </label>
                </div>

                <div id="aiScanningIndicator" style="display: none; margin-top: 20px; color: #00f0ff; font-size: 13px; font-weight: 500;">
                    <span>⚡</span> Analyzing your facial features and scanning wedding photos...
                </div>

                <!-- MATCHED RESULTS -->
                <div id="matchedResultsHeader" style="display: none; justify-content: space-between; align-items: center; margin: 25px 0 15px;">
                    <h4 style="font-size: 18px; color: #fff;">Photos Found: <span id="matchCount">0</span></h4>
                    <button type="button" onclick="resetSelfieSearch()" style="background: transparent; border: 1px solid rgba(255,255,255,0.2); color: #aaa; padding: 6px 14px; border-radius: 20px; font-size: 11px; cursor: pointer;">
                        Scan Another ↺
                    </button>
                </div>
                <div id="matchedPhotosGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; max-height: 320px; overflow-y: auto;"></div>

                <div id="noMatchNotice" style="display: none; text-align: center; padding: 30px 15px;">
                    <h5 style="color: #fff; margin-bottom: 6px;">No Direct Matches Found</h5>
                    <p style="color: #94a3b8; font-size: 12.5px;">Try taking another selfie in better lighting!</p>
                </div>
            </div>
        </div>

        <!-- FULLSCREEN PHOTO & VIDEO PREVIEW MODAL -->
        <div id="mediaPreviewModal" class="modal-overlay" onclick="closeMediaPreviewOnBackdrop(event)">
            <div style="max-width: 92vw; max-height: 92vh; position: relative; text-align: center;">
                <button type="button" class="modal-close" onclick="closeMediaPreview()">&times;</button>
                
                <!-- IMAGE CONTAINER -->
                <div id="photoPreviewContainer" style="display: none;">
                    <img src="" id="previewModalImg" style="max-height: 78vh; max-width: 90vw; object-fit: contain; border-radius: 6px; box-shadow: 0 20px 60px rgba(0,0,0,0.9);" alt="Wedding High Resolution Photo">
                </div>

                <!-- VIDEO CONTAINER -->
                <div id="videoPreviewContainer" style="display: none; width: 85vw; max-width: 950px; aspect-ratio: 16/9; margin: 0 auto; background: #000; border-radius: 8px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.9); border: 1px solid rgba(0, 240, 255, 0.3);">
                    <video id="previewModalVideo" controls style="width: 100%; height: 100%; object-fit: contain;"></video>
                </div>

                <!-- DIRECT DOWNLOAD BUTTON (NEVER EXPOSES DRIVE URL) -->
                <div style="margin-top: 20px;">
                    <a href="" id="previewDownloadBtn" class="film-button" style="padding: 12px 32px; background: linear-gradient(135deg, #0099cc, #00f0ff); color: #070a12; font-weight: 700; text-decoration: none; border-radius: 30px; box-shadow: 0 0 20px rgba(0, 240, 255, 0.4); display: inline-flex; align-items: center; gap: 8px;">
                        <span style="font-size: 16px;">↓</span> <span id="downloadBtnText">Download File</span>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.media-tab-btn {
    background: transparent;
    color: #94a3b8;
    border: 1px solid rgba(255, 255, 255, 0.15);
}
.media-tab-btn:hover {
    color: #fff;
    border-color: rgba(0, 240, 255, 0.4);
}
.media-tab-btn.active {
    background: rgba(0, 240, 255, 0.15) !important;
    color: #00f0ff !important;
    border-color: #00f0ff !important;
    box-shadow: 0 0 15px rgba(0, 240, 255, 0.25);
}
.media-card-item:hover {
    transform: translateY(-4px);
    border-color: rgba(0, 240, 255, 0.5) !important;
    box-shadow: 0 14px 30px rgba(0, 0, 0, 0.8), 0 0 20px rgba(0, 240, 255, 0.15) !important;
}
</style>

@push('scripts')
<script src="/js/face-api.min.js"></script>
<script>
    // FILTER PHOTOS / VIDEOS / ALL
    function setMediaFilter(type) {
        document.querySelectorAll('.media-tab-btn').forEach(btn => btn.classList.remove('active'));
        const allItems = document.querySelectorAll('.media-card-item');

        if (type === 'all') {
            document.getElementById('filterAllBtn').classList.add('active');
            allItems.forEach(el => el.style.display = 'block');
        } else if (type === 'photos') {
            const btn = document.getElementById('filterPhotosBtn');
            if (btn) btn.classList.add('active');
            allItems.forEach(el => {
                el.style.display = el.classList.contains('type-photo') ? 'block' : 'none';
            });
        } else if (type === 'videos') {
            const btn = document.getElementById('filterVideosBtn');
            if (btn) btn.classList.add('active');
            allItems.forEach(el => {
                el.style.display = el.classList.contains('type-video') ? 'block' : 'none';
            });
        }
    }

    // PREVIEW MODAL (PHOTOS & VIDEOS)
    function openMediaPreview(fileUrl, downloadUrl, isVideo, fileName) {
        const modal = document.getElementById('mediaPreviewModal');
        const photoBox = document.getElementById('photoPreviewContainer');
        const videoBox = document.getElementById('videoPreviewContainer');
        const img = document.getElementById('previewModalImg');
        const vid = document.getElementById('previewModalVideo');
        const dBtn = document.getElementById('previewDownloadBtn');
        const dText = document.getElementById('downloadBtnText');

        dBtn.href = downloadUrl;

        if (isVideo) {
            photoBox.style.display = 'none';
            videoBox.style.display = 'block';
            vid.src = fileUrl;
            vid.play();
            dText.innerText = 'Download Video (' + (fileName || 'Original Master') + ') ↓';
        } else {
            videoBox.style.display = 'none';
            vid.pause();
            vid.src = '';
            photoBox.style.display = 'block';
            img.src = fileUrl;
            dText.innerText = 'Download High-Res Photo ↓';
        }

        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMediaPreview() {
        const modal = document.getElementById('mediaPreviewModal');
        const vid = document.getElementById('previewModalVideo');
        if (vid) {
            vid.pause();
            vid.src = '';
        }
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    function closeMediaPreviewOnBackdrop(e) {
        if (e.target.id === 'mediaPreviewModal') {
            closeMediaPreview();
        }
    }

    // PIN VERIFICATION
    function submitPin(e) {
        e.preventDefault();
        const pin = document.getElementById('pinInput').value;
        const btn = document.getElementById('pinBtn');
        const err = document.getElementById('pinError');

        btn.disabled = true;
        btn.innerText = 'Verifying Passcode...';

        fetch('/gallery/{{ $album->slug }}/verify-pin', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
            body: JSON.stringify({ pin: pin }),
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerText = 'Unlock Collection →';
            if (data.success) {
                window.location.reload();
            } else {
                err.innerText = data.message || 'Incorrect passcode. Please try again.';
                err.style.display = 'block';
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerText = 'Unlock Collection →';
            alert('Verification error. Please check your network and try again.');
        });
    }

    // AI SELFIE MODAL HANDLING
    function toggleSelfieModal() {
        const modal = document.getElementById('selfieModal');
        if (modal.classList.contains('active')) {
            modal.classList.remove('active');
            stopWebcam();
            document.body.style.overflow = '';
        } else {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeSelfieOnBackdrop(e) {
        if (e.target.id === 'selfieModal') {
            toggleSelfieModal();
        }
    }

    // AI Models Loading
    let modelsLoaded = false;
    let albumPhotosData = null;

    async function ensureModelsLoaded() {
        if (modelsLoaded) return;
        const MODEL_URL = '/models';
        await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);
        await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
        await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
        modelsLoaded = true;
    }

    async function loadAlbumDescriptors() {
        if (albumPhotosData) return albumPhotosData;
        const res = await fetch('/gallery/{{ $album->slug }}/photos-data');
        albumPhotosData = await res.json();
        return albumPhotosData;
    }

    // Webcam Handling
    let webcamStream = null;

    async function startWebcam() {
        const video = document.getElementById('webcamVideo');
        const camWrapper = document.getElementById('cameraWrapper');
        const startBtn = document.getElementById('startCamBtn');
        const snapBtn = document.getElementById('snapBtn');

        try {
            webcamStream = await navigator.mediaDevices.getUserMedia({
                video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }
            });
            video.srcObject = webcamStream;
            camWrapper.style.display = 'block';
            startBtn.style.display = 'none';
            snapBtn.style.display = 'inline-block';
            await ensureModelsLoaded();
        } catch (err) {
            alert('Could not access camera: ' + err.message + '. Please allow camera permissions or upload a photo instead.');
        }
    }

    function stopWebcam() {
        if (webcamStream) {
            webcamStream.getTracks().forEach(track => track.stop());
            webcamStream = null;
        }
        const camWrapper = document.getElementById('cameraWrapper');
        if (camWrapper) camWrapper.style.display = 'none';
        const startBtn = document.getElementById('startCamBtn');
        if (startBtn) startBtn.style.display = 'inline-block';
        const snapBtn = document.getElementById('snapBtn');
        if (snapBtn) snapBtn.style.display = 'none';
    }

    async function captureSelfie() {
        const video = document.getElementById('webcamVideo');
        const canvas = document.getElementById('selfieCanvas');
        canvas.width = video.videoWidth || 640;
        canvas.height = video.videoHeight || 480;
        const ctx = canvas.getContext('2d');
        ctx.translate(canvas.width, 0);
        ctx.scale(-1, 1);
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        stopWebcam();
        await matchFaceWithAlbum(canvas);
    }

    async function handleFileSelfie(event) {
        const file = event.target.files[0];
        if (!file) return;

        const img = await faceapi.bufferToImage(file);
        const canvas = document.getElementById('selfieCanvas');
        canvas.width = img.width;
        canvas.height = img.height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0);

        stopWebcam();
        await matchFaceWithAlbum(canvas);
    }

    async function matchFaceWithAlbum(canvas) {
        const indicator = document.getElementById('aiScanningIndicator');
        const resultsHeader = document.getElementById('matchedResultsHeader');
        const resultsGrid = document.getElementById('matchedPhotosGrid');
        const noMatch = document.getElementById('noMatchNotice');

        indicator.style.display = 'block';
        resultsHeader.style.display = 'none';
        resultsGrid.innerHTML = '';
        noMatch.style.display = 'none';

        try {
            await ensureModelsLoaded();
            const detection = await faceapi.detectSingleFace(canvas).withFaceLandmarks().withFaceDescriptor();

            if (!detection) {
                indicator.style.display = 'none';
                alert('No face detected in your selfie. Please ensure your face is clearly visible and well-lit.');
                startWebcam();
                return;
            }

            const selfieDescriptor = detection.descriptor;
            const data = await loadAlbumDescriptors();
            const matchedPhotos = [];
            const threshold = 0.52;

            data.photos.forEach(photo => {
                if (photo.face_descriptors && Array.isArray(photo.face_descriptors)) {
                    for (let desc of photo.face_descriptors) {
                        const dist = faceapi.euclideanDistance(selfieDescriptor, desc);
                        if (dist < threshold) {
                            matchedPhotos.push(photo);
                            break;
                        }
                    }
                }
            });

            indicator.style.display = 'none';

            if (matchedPhotos.length === 0) {
                noMatch.style.display = 'block';
            } else {
                resultsHeader.style.display = 'flex';
                document.getElementById('matchCount').innerText = matchedPhotos.length;

                matchedPhotos.forEach(p => {
                    const card = document.createElement('div');
                    card.style.cssText = 'aspect-ratio: 1; border-radius: 6px; overflow: hidden; position: relative; border: 1px solid rgba(0,240,255,0.2); background: #060911;';
                    card.innerHTML = `
                        <img src="${p.thumbnail_url || p.url}" alt="Matched Photo" style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;" onclick="openMediaPreview('${p.url}', '${p.download_url}', ${p.is_video ? 'true' : 'false'}, '${p.file_name || ''}')">
                        <div style="position: absolute; bottom: 6px; right: 6px;">
                            <a href="${p.download_url}" style="background: rgba(10, 14, 24, 0.85); border: 1px solid rgba(0,240,255,0.4); color: #00f0ff; padding: 4px 8px; border-radius: 12px; font-size: 10px; text-decoration: none;">Download ↓</a>
                        </div>
                    `;
                    resultsGrid.appendChild(card);
                });
            }

        } catch (err) {
            console.error(err);
            indicator.style.display = 'none';
            alert('Face matching error: ' + err.message);
        }
    }

    function resetSelfieSearch() {
        document.getElementById('matchedResultsHeader').style.display = 'none';
        document.getElementById('matchedPhotosGrid').innerHTML = '';
        document.getElementById('noMatchNotice').style.display = 'none';
        startWebcam();
    }
</script>
@endpush
@endsection