<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 24px;">

        <!-- 2 PRIMARY SECTION TABS -->
        <div style="display: flex; gap: 12px; border-bottom: 2px solid rgba(255,255,255,0.08); padding-bottom: 16px; flex-wrap: wrap;">
            <button 
                type="button" 
                wire:click="setActiveTab('drive')" 
                style="padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; border: 1px solid {{ $activeTab === 'drive' ? '#00f0ff' : 'rgba(255,255,255,0.12)' }}; background: {{ $activeTab === 'drive' ? 'rgba(0, 240, 255, 0.15)' : '#18181b' }}; color: {{ $activeTab === 'drive' ? '#00f0ff' : '#a1a1aa' }}; display: flex; align-items: center; gap: 8px; box-shadow: {{ $activeTab === 'drive' ? '0 0 15px rgba(0,240,255,0.2)' : 'none' }};">
                <span>📁</span> Section 1: Client VIP Delivery (Google Drive Photos & Videos)
            </button>

            <button 
                type="button" 
                wire:click="setActiveTab('ai')" 
                style="padding: 12px 24px; border-radius: 8px; font-size: 14px; font-weight: 700; cursor: pointer; border: 1px solid {{ $activeTab === 'ai' ? '#c4a472' : 'rgba(255,255,255,0.12)' }}; background: {{ $activeTab === 'ai' ? 'rgba(196, 164, 114, 0.15)' : '#18181b' }}; color: {{ $activeTab === 'ai' ? '#c4a472' : '#a1a1aa' }}; display: flex; align-items: center; gap: 8px; box-shadow: {{ $activeTab === 'ai' ? '0 0 15px rgba(196,164,114,0.2)' : 'none' }};">
                <span>🤖</span> Section 2: Guest AI Face Recognition & Banquet QR
            </button>
        </div>

        @if($activeTab === 'drive')
            <!-- ======================================================== -->
            <!-- SECTION 1: CLIENT VIP DELIVERY (GOOGLE DRIVE & FILES)    -->
            <!-- ======================================================== -->

            <!-- CLIENT VIP HEADER & LINK -->
            <div style="background: rgba(18, 25, 41, 0.95); border: 1px solid rgba(0, 240, 255, 0.2); border-radius: 12px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 16px;">
                    <div>
                        <span style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #00f0ff; font-weight: 600;">
                            CLIENT VIP DELIVERY PORTAL
                        </span>
                        <h2 style="font-size: 24px; font-weight: 700; color: #fff; margin: 4px 0 8px;">
                            {{ $record->title }}
                        </h2>
                        <div style="font-size: 13.5px; color: #94a3b8; display: flex; gap: 20px; flex-wrap: wrap;">
                            <span>📍 {{ $record->location ?: 'Location TBD' }}</span>
                            <span>📅 {{ $record->event_date ? $record->event_date->format('d M Y') : 'Date TBD' }}</span>
                            <span style="background: rgba(0, 240, 255, 0.1); border: 1px solid rgba(0,240,255,0.3); color: #00f0ff; padding: 2px 10px; border-radius: 20px;">
                                🔒 Client Access PIN: <strong>{{ $record->pin_code ?: 'None (Public)' }}</strong>
                            </span>
                        </div>
                    </div>

                    <!-- CLIENT SHARE ACTIONS -->
                    <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                        <a href="{{ $record->guest_url }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; background: linear-gradient(135deg, #0099cc, #00f0ff); color: #070a12; font-weight: 700; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-size: 13px; box-shadow: 0 0 15px rgba(0,240,255,0.3);">
                            Open Client Portal ↗
                        </a>
                        <button type="button" onclick="navigator.clipboard.writeText('{{ $record->guest_url }}'); alert('Client link copied to clipboard!');" style="background: #27272a; border: 1px solid rgba(255,255,255,0.15); color: #fff; padding: 10px 16px; border-radius: 8px; cursor: pointer; font-size: 13px;">
                            Copy Client Link
                        </button>
                    </div>
                </div>

                <!-- STATS BAR -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.08);">
                    <div style="background: #090d16; padding: 14px 18px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase;">Total Photos</div>
                        <div style="font-size: 24px; font-weight: 700; color: #fff; margin-top: 4px;">
                            {{ $record->photos()->where('is_video', false)->count() }}
                        </div>
                    </div>
                    <div style="background: #090d16; padding: 14px 18px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase;">🎬 Cinema Films (Videos)</div>
                        <div style="font-size: 24px; font-weight: 700; color: #00f0ff; margin-top: 4px;">
                            {{ $record->photos()->where('is_video', true)->count() }}
                        </div>
                    </div>
                    <div style="background: #090d16; padding: 14px 18px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase;">Direct Downloads</div>
                        <div style="font-size: 14px; font-weight: 600; color: #00ff9d; margin-top: 8px;">
                            ✓ Active via Website Stream
                        </div>
                    </div>
                </div>
            </div>

            <!-- GOOGLE DRIVE SYNC & LOCAL UPLOAD CARDS -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">

                <!-- GOOGLE DRIVE SYNC -->
                <div style="background: #121929; border: 1px solid rgba(0, 240, 255, 0.25); border-radius: 12px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                        <span>📁</span> Sync Google Drive Folder (Photos & Videos)
                    </h3>
                    <p style="font-size: 13px; color: #94a3b8; margin-bottom: 16px; line-height: 1.5;">
                        Paste your Google Drive folder link. The system will automatically import all photos and wedding video files. <strong>Your Google Drive link is 100% hidden</strong> from clients!
                    </p>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 12px; color: #94a3b8; margin-bottom: 6px;">Google Drive Folder Link:</label>
                        <input 
                            type="text" 
                            wire:model="googleDriveInput" 
                            placeholder="https://drive.google.com/drive/folders/1aBcDe..." 
                            style="width: 100%; background: #080c14; border: 1px solid rgba(0,240,255,0.3); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 13px;"
                        >
                    </div>

                    <button 
                        type="button" 
                        wire:click="syncFromGoogleDrive" 
                        wire:loading.attr="disabled"
                        style="background: linear-gradient(135deg, #0099cc, #00f0ff); color: #070a12; font-weight: 700; padding: 12px 20px; border-radius: 8px; border: none; cursor: pointer; font-size: 13px; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 0 15px rgba(0,240,255,0.3);">
                        <span wire:loading.remove wire:target="syncFromGoogleDrive">🔄 Sync Photos & Videos from Google Drive</span>
                        <span wire:loading wire:target="syncFromGoogleDrive">⏳ Connecting & Syncing Media...</span>
                    </button>
                </div>

                <!-- DIRECT LOCAL UPLOAD -->
                <div style="background: #121929; border: 1px solid rgba(255,255,255,0.1); border-radius: 12px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                        <span>📤</span> Or: Direct Server Upload
                    </h3>
                    <p style="font-size: 13px; color: #94a3b8; margin-bottom: 16px; line-height: 1.5;">
                        Alternatively, you can upload wedding photos directly from your computer to the server.
                    </p>

                    <form wire:submit.prevent="saveUploadedPhotos">
                        <input 
                            type="file" 
                            wire:model="uploadedPhotos" 
                            multiple 
                            accept="image/*"
                            style="width: 100%; background: #080c14; border: 2px dashed rgba(255,255,255,0.2); padding: 16px; border-radius: 8px; color: #fff; cursor: pointer; margin-bottom: 12px; font-size: 12px;"
                        >

                        <div wire:loading wire:target="uploadedPhotos" style="color: #00f0ff; font-size: 13px; margin-bottom: 10px;">
                            ⏳ Preparing photos for upload...
                        </div>

                        @if(count($uploadedPhotos) > 0)
                            <button type="submit" wire:loading.attr="disabled" style="background: #00ff9d; color: #070a12; font-weight: 700; padding: 12px 20px; border-radius: 8px; border: none; cursor: pointer; font-size: 13px; width: 100%;">
                                Save {{ count($uploadedPhotos) }} Photos to Album
                            </button>
                        @endif
                    </form>
                </div>
            </div>

            <!-- MEDIA GALLERY LIST -->
            <div style="background: #121929; border: 1px solid rgba(0, 240, 255, 0.15); border-radius: 12px; padding: 24px;">
                <h3 style="font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                    <span>🖼️ Client Media Items ({{ $record->photos()->count() }} total)</span>
                    <span style="font-size: 12px; color: #94a3b8; font-weight: normal;">All files can be downloaded directly from your website</span>
                </h3>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 16px;">
                    @forelse($record->photos()->latest()->get() as $p)
                        <div style="background: #080c14; border-radius: 8px; overflow: hidden; border: 1px solid rgba(255,255,255,0.08); position: relative;">
                            @if($p->is_video)
                                <div style="width: 100%; aspect-ratio: 1; background: #030508; display: flex; align-items: center; justify-content: center; position: relative;">
                                    <div style="font-size: 32px;">🎬</div>
                                    <span style="position: absolute; top: 8px; left: 8px; background: rgba(0,0,0,0.8); border: 1px solid #00f0ff; color: #00f0ff; font-size: 9px; padding: 2px 6px; border-radius: 10px; font-weight: 700;">VIDEO</span>
                                </div>
                            @else
                                <img 
                                    src="{{ $p->thumbnail_url ?: $p->full_url }}" 
                                    alt="{{ $p->file_name }}"
                                    loading="lazy"
                                    style="width: 100%; aspect-ratio: 1; object-fit: cover; display: block;"
                                >
                            @endif

                            <div style="padding: 10px; display: flex; justify-content: space-between; align-items: center; font-size: 11px; color: #94a3b8;">
                                <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 120px;">
                                    {{ $p->file_name ?: 'Media' }}
                                </span>

                                <button 
                                    type="button" 
                                    wire:click="deletePhoto({{ $p->id }})" 
                                    wire:confirm="Are you sure you want to remove this file?"
                                    style="background: transparent; border: none; color: #ff007f; cursor: pointer; font-size: 14px;">
                                    🗑️
                                </button>
                            </div>
                        </div>
                    @empty
                        <div style="grid-column: 1 / -1; text-align: center; color: #71717a; padding: 60px 0; font-size: 14px;">
                            No photos or videos uploaded to this album yet. Paste your Google Drive link above to sync!
                        </div>
                    @endforelse
                </div>
            </div>

        @else
            <!-- ======================================================== -->
            <!-- SECTION 2: GUEST AI FACE RECOGNITION & TABLE QR CODE     -->
            <!-- ======================================================== -->

            <div style="background: rgba(18, 25, 41, 0.95); border: 1px solid rgba(196, 164, 114, 0.25); border-radius: 12px; padding: 24px;">
                <span style="font-size: 11px; letter-spacing: 2px; text-transform: uppercase; color: #c4a472; font-weight: 600;">
                    GUEST SELFIE FINDER & BANQUET FLYERS
                </span>
                <h2 style="font-size: 24px; font-weight: 700; color: #fff; margin: 4px 0 8px;">
                    Banquet AI Facial Recognition Setup
                </h2>
                <p style="font-size: 13.5px; color: #94a3b8; max-width: 750px; line-height: 1.6;">
                    Print table QR codes for wedding dining tables. When guests scan with their phones, they take a quick selfie, and the AI instantly presents every wedding photo they appear in!
                </p>

                <!-- AI STATS -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.08);">
                    <div style="background: #090d16; padding: 14px 18px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase;">Total Album Photos</div>
                        <div style="font-size: 24px; font-weight: 700; color: #fff; margin-top: 4px;">
                            {{ $record->photos()->where('is_video', false)->count() }}
                        </div>
                    </div>
                    <div style="background: #090d16; padding: 14px 18px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase;">AI Indexed Photos</div>
                        <div style="font-size: 24px; font-weight: 700; color: #4ade80; margin-top: 4px;">
                            {{ $record->photos()->whereNotNull('face_descriptors')->count() }}
                        </div>
                    </div>
                    <div style="background: #090d16; padding: 14px 18px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase;">Faces Detected</div>
                        <div style="font-size: 24px; font-weight: 700; color: #c4a472; margin-top: 4px;">
                            {{ $record->photos()->sum('faces_count') }}
                        </div>
                    </div>
                    <div style="background: #090d16; padding: 14px 18px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.06);">
                        <div style="font-size: 11px; color: #94a3b8; text-transform: uppercase;">Pending AI Scan</div>
                        <div style="font-size: 24px; font-weight: 700; color: #facc15; margin-top: 4px;">
                            {{ $record->photos()->where('is_video', false)->whereNull('face_descriptors')->count() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3 STEPS FOR GUEST AI: DRIVE SYNC -> FACE SCAN -> TABLE QR -->
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 24px;">

                <!-- STEP 1: GOOGLE DRIVE GUEST PHOTOS SYNC -->
                <div style="background: #121929; border: 1px solid rgba(196, 164, 114, 0.35); border-radius: 12px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                        <span>📁</span> Step 1: Sync Guest Photos from Google Drive
                    </h3>
                    <p style="font-size: 13px; color: #94a3b8; margin-bottom: 16px; line-height: 1.5;">
                        Paste the Google Drive link containing wedding guest & banquet photos. These will be scanned by the AI for guest selfie matching!
                    </p>

                    <div style="margin-bottom: 16px;">
                        <label style="display: block; font-size: 12px; color: #c4a472; margin-bottom: 6px;">Guest Photos Google Drive Folder Link:</label>
                        <input 
                            type="text" 
                            wire:model="guestGoogleDriveInput" 
                            placeholder="https://drive.google.com/drive/folders/..." 
                            style="width: 100%; background: #080c14; border: 1px solid rgba(196,164,114,0.4); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 13px;"
                        >
                    </div>

                    <button 
                        type="button" 
                        wire:click="syncGuestPhotosFromGoogleDrive" 
                        wire:loading.attr="disabled"
                        style="background: #c4a472; color: #070a12; font-weight: 700; padding: 12px 20px; border-radius: 8px; border: none; cursor: pointer; font-size: 13px; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 0 15px rgba(196,164,114,0.3);">
                        <span wire:loading.remove wire:target="syncGuestPhotosFromGoogleDrive">🔄 Sync Guest Photos from Google Drive</span>
                        <span wire:loading wire:target="syncGuestPhotosFromGoogleDrive">⏳ Connecting & Syncing Guest Photos...</span>
                    </button>
                </div>

                <!-- STEP 2: RUN AI BIOMETRIC INDEXER CARD -->
                <div style="background: #121929; border: 1px solid rgba(196, 164, 114, 0.25); border-radius: 12px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 8px; display: flex; align-items: center; gap: 8px;">
                        <span>🤖</span> Step 2: Run AI Face Indexer
                    </h3>
                    <p style="font-size: 13px; color: #94a3b8; margin-bottom: 16px; line-height: 1.5;">
                        After syncing photos from Drive above, click below. The AI will detect all faces and index biometric vectors for instant guest selfie matching.
                    </p>

                    <button 
                        type="button" 
                        id="startIndexingBtn"
                        onclick="startAiFaceIndexing()" 
                        style="background: linear-gradient(135deg, #0099cc, #00f0ff); color: #070a12; font-weight: 700; padding: 13px 20px; border-radius: 8px; border: none; cursor: pointer; font-size: 13px; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px; box-shadow: 0 0 15px rgba(0,240,255,0.3);">
                        <span>⚡</span> Scan & Index Album Faces Now
                    </button>

                    <!-- PROGRESS BOX -->
                    <div id="aiIndexingProgressBox" style="display: none; margin-top: 16px;">
                        <div style="display: flex; justify-content: space-between; font-size: 13px; color: #fff; margin-bottom: 6px;">
                            <span id="aiProgressText">Initializing Neural Models...</span>
                            <span id="aiProgressPercent">0%</span>
                        </div>
                        <div style="width: 100%; background: #080c14; height: 10px; border-radius: 5px; overflow: hidden; border: 1px solid rgba(255,255,255,0.1);">
                            <div id="aiProgressBar" style="width: 0%; height: 100%; background: #00f0ff; transition: width 0.3s;"></div>
                        </div>
                        <div id="aiFacesDetectedLog" style="font-size: 12px; color: #4ade80; margin-top: 8px;"></div>
                    </div>
                </div>

                <!-- STEP 3: PRINT TABLE QR CODE CARD -->
                <div style="background: #121929; border: 1px solid rgba(196, 164, 114, 0.25); border-radius: 12px; padding: 24px;">
                    <h3 style="font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 6px; display: flex; align-items: center; gap: 8px;">
                        <span>📱</span> Step 3: Print Banquet Table QR Code
                    </h3>
                    <p style="font-size: 13px; color: #94a3b8; margin-bottom: 16px; line-height: 1.5;">
                        Print this QR code on dining tables. Guests point their phone cameras, snap a selfie, and find all their photos instantly!
                    </p>

                    <div style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
                        <img 
                            src="https://api.qrserver.com/v1/create-qr-code/?size=140x140&data={{ urlencode($record->guest_url) }}" 
                            alt="Guest Portal QR Code" 
                            style="width: 95px; height: 95px; border-radius: 8px; border: 3px solid #c4a472; background: #fff;"
                        >
                        <div>
                            <button type="button" onclick="window.print()" style="background: #c4a472; color: #070a12; font-weight: 700; padding: 10px 18px; border-radius: 8px; border: none; font-size: 12.5px; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                                Print QR Flyer 🖨️
                            </button>
                            <p style="font-size: 11px; color: #94a3b8; margin-top: 6px;">Works on all iPhone & Android cameras</p>
                        </div>
                    </div>
                </div>

            </div>

        @endif

    </div>

    <!-- SCRIPT FOR FACE-API.JS INDEXER -->
    <script src="/js/face-api.min.js"></script>
    <script>
        let isModelsLoaded = false;

        async function loadAiModels() {
            if (isModelsLoaded) return;
            const MODEL_URL = '/models';
            await faceapi.nets.ssdMobilenetv1.loadFromUri(MODEL_URL);
            await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
            await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);
            isModelsLoaded = true;
        }

        async function startAiFaceIndexing() {
            const btn = document.getElementById('startIndexingBtn');
            const progressBox = document.getElementById('aiIndexingProgressBox');
            const progressText = document.getElementById('aiProgressText');
            const progressPercent = document.getElementById('aiProgressPercent');
            const progressBar = document.getElementById('aiProgressBar');
            const facesLog = document.getElementById('aiFacesDetectedLog');

            btn.disabled = true;
            btn.innerText = 'Scanning In Progress...';
            progressBox.style.display = 'block';

            try {
                progressText.innerText = 'Loading Neural Network Models...';
                await loadAiModels();

                const res = await fetch('/gallery/{{ $record->slug }}/photos-data');
                const data = await res.json();
                const photos = (data.photos || []).filter(p => !p.is_video);

                if (photos.length === 0) {
                    alert('No photos to index! Please sync photos from Google Drive or upload photos first.');
                    btn.disabled = false;
                    btn.innerText = '⚡ Scan & Index Album Faces Now';
                    progressBox.style.display = 'none';
                    return;
                }

                let totalFacesFound = 0;
                let scannedCount = 0;

                for (let i = 0; i < photos.length; i++) {
                    const photo = photos[i];
                    progressText.innerText = `Analyzing Photo ${i + 1} of ${photos.length}...`;
                    const pct = Math.round(((i + 1) / photos.length) * 100);
                    progressBar.style.width = pct + '%';
                    progressPercent.innerText = pct + '%';

                    try {
                        const img = new Image();
                        img.crossOrigin = 'anonymous';
                        img.src = photo.url;
                        await new Promise(resolve => { img.onload = resolve; img.onerror = resolve; });

                        const detections = await faceapi
                            .detectAllFaces(img)
                            .withFaceLandmarks()
                            .withFaceDescriptors();

                        const descriptors = detections.map(d => Array.from(d.descriptor));
                        totalFacesFound += descriptors.length;

                        await fetch(`/admin/photos/${photo.id}/descriptors`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            body: JSON.stringify({ descriptors: descriptors }),
                        });

                        scannedCount++;
                        facesLog.innerText = `✓ Total Faces Detected So Far: ${totalFacesFound}`;
                    } catch (e) {
                        console.error('Error scanning photo ' + photo.id, e);
                    }
                }

                progressText.innerText = `Indexing Complete! Scanned ${scannedCount} photos, found ${totalFacesFound} faces.`;
                progressBar.style.background = '#22c55e';
                btn.disabled = false;
                btn.innerText = '✓ Album Successfully Indexed!';
                setTimeout(() => { window.location.reload(); }, 2000);

            } catch (err) {
                console.error(err);
                alert('Error running face recognition: ' + err.message);
                btn.disabled = false;
                btn.innerText = '⚡ Scan & Index Album Faces Now';
            }
        }
    </script>
</x-filament-panels::page>