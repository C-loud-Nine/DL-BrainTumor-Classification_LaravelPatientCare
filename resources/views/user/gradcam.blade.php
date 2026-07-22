<!DOCTYPE html>
<html lang="en">
<head>
    <x-header />

<style>
/* ============================================================
   Grad-CAM page. Palette roles are declared once here and used
   by name below, so the whole page retunes from one block.
   ============================================================ */
.gc-root {
    --plane:        #f9f9f7;   /* page plane            */
    --surface:      #fcfcfb;   /* card surface          */
    --ink:          #0b0b0b;   /* primary ink           */
    --ink-2:        #52514e;   /* secondary ink         */
    --ink-muted:    #898781;   /* muted / axis          */
    --hairline:     rgba(11, 11, 11, 0.10);
    --rule:         #e1e0d9;

    --accent:       #2a78d6;   /* blue 450 - predicted  */
    --accent-soft:  #86b6ef;   /* blue 250 - other bars */
    --track:        #cde2fb;   /* blue 100 - meter track*/

    --warning:      #fab219;
    --critical:     #d03b3b;
    --good:         #0ca30c;

    --viewer:       #0d0d0d;   /* radiology panel plane */

    --r-lg: 16px;
    --r-md: 12px;
    --r-sm: 8px;

    font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
    color: var(--ink);
    background: var(--plane);
    padding: 56px 24px 72px;
}

.gc-shell { max-width: 1120px; margin: 0 auto; }

/* ---------- page head ---------- */
.gc-head { text-align: center; margin-bottom: 40px; }

.gc-eyebrow {
    display: inline-flex; align-items: center; gap: 8px;
    font-size: 0.75rem; font-weight: 600; letter-spacing: 0.08em;
    text-transform: uppercase; color: var(--accent);
    background: rgba(42, 120, 214, 0.08);
    border: 1px solid rgba(42, 120, 214, 0.18);
    padding: 6px 14px; border-radius: 999px; margin-bottom: 18px;
}

.gc-title {
    font-size: clamp(1.9rem, 4vw, 2.6rem);
    font-weight: 700; letter-spacing: -0.02em;
    margin: 0 0 10px; color: var(--ink);
}

.gc-sub {
    font-size: 1rem; color: var(--ink-2);
    max-width: 620px; margin: 0 auto; line-height: 1.6;
}

/* ---------- cards ---------- */
.gc-card {
    background: var(--surface);
    border: 1px solid var(--hairline);
    border-radius: var(--r-lg);
    box-shadow: 0 1px 2px rgba(11,11,11,0.04), 0 8px 24px rgba(11,11,11,0.05);
}

.gc-card + .gc-card { margin-top: 28px; }
.gc-card-body { padding: 28px; }

.gc-card-head {
    display: flex; align-items: center; justify-content: space-between;
    gap: 16px; flex-wrap: wrap;
    padding: 20px 28px; border-bottom: 1px solid var(--hairline);
}

.gc-card-title {
    font-size: 1.05rem; font-weight: 650; margin: 0;
    display: flex; align-items: center; gap: 10px;
}

.gc-card-title i { color: var(--accent); }

/* ---------- form ---------- */
.gc-field-label {
    display: block; font-size: 0.8rem; font-weight: 600;
    text-transform: uppercase; letter-spacing: 0.05em;
    color: var(--ink-2); margin-bottom: 10px;
}

.gc-drop {
    position: relative; display: flex; flex-direction: column;
    align-items: center; justify-content: center; gap: 8px;
    min-height: 148px; padding: 24px;
    border: 1.5px dashed #c9c8c1; border-radius: var(--r-md);
    background: #fafaf8; cursor: pointer;
    transition: border-color .18s ease, background .18s ease;
    text-align: center;
}

.gc-drop:hover, .gc-drop.is-over { border-color: var(--accent); background: rgba(42,120,214,0.04); }
.gc-drop input[type=file] { position: absolute; inset: 0; opacity: 0; cursor: pointer; }
.gc-drop i { font-size: 1.6rem; color: var(--accent); }
.gc-drop-main { font-weight: 600; font-size: 0.95rem; }
.gc-drop-hint { font-size: 0.82rem; color: var(--ink-muted); }
.gc-drop.has-file .gc-drop-hint { color: var(--good); font-weight: 600; }

.gc-select {
    width: 100%; padding: 13px 14px; font-size: 0.95rem;
    border: 1px solid #d7d6ce; border-radius: var(--r-md);
    background: #fff; color: var(--ink); cursor: pointer;
    font-family: inherit;
}

.gc-select:focus, .gc-drop:focus-within {
    outline: none; border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(42,120,214,0.16);
}

.gc-actions { display: flex; justify-content: center; margin-top: 26px; }

.gc-btn {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 14px 34px; font-size: 1rem; font-weight: 600;
    color: #fff; background: var(--accent);
    border: none; border-radius: var(--r-md); cursor: pointer;
    font-family: inherit;
    box-shadow: 0 2px 8px rgba(42,120,214,0.28);
    transition: background .18s ease, transform .12s ease, box-shadow .18s ease;
}

.gc-btn:hover { background: #256abf; box-shadow: 0 4px 14px rgba(42,120,214,0.34); }
.gc-btn:active { transform: translateY(1px); }
.gc-btn[disabled] { opacity: .65; cursor: progress; }
.gc-btn .gc-spin { display: none; }
.gc-btn.is-busy .gc-spin { display: inline-block; animation: gc-rot 0.8s linear infinite; }
.gc-btn.is-busy .gc-idle { display: none; }
@keyframes gc-rot { to { transform: rotate(360deg); } }

/* ---------- badges / alert ---------- */
.gc-badges { display: flex; gap: 8px; flex-wrap: wrap; }

.gc-badge {
    display: inline-flex; align-items: center; gap: 6px;
    font-size: 0.75rem; font-weight: 600;
    padding: 5px 11px; border-radius: 999px;
    background: #f1f0ec; color: var(--ink-2);
    border: 1px solid var(--hairline);
}

.gc-badge.is-accent {
    background: rgba(42,120,214,0.09); color: #1c5cab;
    border-color: rgba(42,120,214,0.2);
}

/* status = icon + label, never colour alone */
.gc-alert {
    display: flex; align-items: flex-start; gap: 12px;
    margin: 0 28px 4px; padding: 16px 18px;
    border-radius: var(--r-md); font-size: 0.92rem; line-height: 1.55;
    background: #fff8e6; border: 1px solid rgba(250,178,25,0.45); color: #6b4c00;
}

.gc-alert i { color: #a37200; font-size: 1.05rem; margin-top: 2px; }

/* ---------- viewer ---------- */
.gc-viewer {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 22px; padding: 28px;
    align-items: start;   /* panes hug their content; no dead black space */
}

.gc-pane {
    background: var(--viewer);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: var(--r-md);
    overflow: hidden;
    display: flex; flex-direction: column;
}

.gc-pane-bar {
    display: flex; align-items: center; justify-content: space-between;
    gap: 10px; padding: 11px 14px;
    background: rgba(255,255,255,0.05);
    border-bottom: 1px solid rgba(255,255,255,0.08);
}

.gc-pane-name {
    font-size: 0.78rem; font-weight: 600; letter-spacing: 0.06em;
    text-transform: uppercase; color: #e8e8e4;
}

.gc-pane-tag { font-size: 0.7rem; color: #9b9a93; }

.gc-frame {
    position: relative; width: 100%; aspect-ratio: 1 / 1;
    background: #000; display: block;
}

.gc-frame img {
    position: absolute; inset: 0;
    width: 100%; height: 100%; object-fit: contain;
    display: block;
}

#gcOverlay { transition: opacity .12s linear; }

.gc-pane-foot { padding: 12px 14px 14px; }

/* blend control */
.gc-blend-row { display: flex; align-items: center; gap: 10px; }

.gc-range {
    -webkit-appearance: none; appearance: none;
    flex: 1; height: 5px; border-radius: 999px; cursor: pointer;
    background: linear-gradient(to right, #6b6b66, var(--accent));
}

.gc-range::-webkit-slider-thumb {
    -webkit-appearance: none; appearance: none;
    width: 16px; height: 16px; border-radius: 50%;
    background: #fff; border: 3px solid var(--accent); cursor: grab;
    box-shadow: 0 1px 4px rgba(0,0,0,0.5);
}

.gc-range::-moz-range-thumb {
    width: 16px; height: 16px; border-radius: 50%;
    background: #fff; border: 3px solid var(--accent); cursor: grab;
}

.gc-blend-val {
    font-size: 0.72rem; color: #b8b7b0; width: 62px; text-align: right;
    font-variant-numeric: tabular-nums;
}

.gc-presets { display: flex; gap: 6px; margin-top: 12px; }

.gc-preset {
    flex: 1; padding: 7px 4px; font-size: 0.72rem; font-weight: 600;
    color: #cfcec8; background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.10);
    border-radius: var(--r-sm); cursor: pointer; font-family: inherit;
    transition: background .15s ease, color .15s ease;
}

.gc-preset:hover { background: rgba(255,255,255,0.14); color: #fff; }
.gc-preset.is-on { background: var(--accent); border-color: var(--accent); color: #fff; }

/* JET legend */
.gc-legend { display: flex; align-items: center; gap: 10px; margin-top: 14px; }

.gc-legend-bar {
    flex: 1; height: 8px; border-radius: 999px;
    background: linear-gradient(to right,
        #00007f, #0000ff, #007fff, #00ffff,
        #7fff7f, #ffff00, #ff7f00, #ff0000, #7f0000);
}

.gc-legend-cap { font-size: 0.68rem; color: #9b9a93; white-space: nowrap; }

/* ---------- result ---------- */
.gc-result { display: grid; grid-template-columns: 0.85fr 1.15fr; gap: 28px; padding: 28px; }

.gc-verdict {
    padding: 24px; border-radius: var(--r-md);
    background: linear-gradient(180deg, rgba(42,120,214,0.06), rgba(42,120,214,0.02));
    border: 1px solid rgba(42,120,214,0.18);
    display: flex; flex-direction: column; justify-content: center;
}

.gc-kicker {
    font-size: 0.75rem; font-weight: 600; letter-spacing: 0.07em;
    text-transform: uppercase; color: var(--ink-muted); margin: 0 0 8px;
}

.gc-class {
    font-size: clamp(1.6rem, 3.4vw, 2.1rem); font-weight: 700;
    text-transform: capitalize; letter-spacing: -0.015em;
    color: var(--ink); margin: 0 0 22px; line-height: 1.15;
}

/* the one hero figure on this view */
.gc-hero {
    font-size: 3rem; font-weight: 700; line-height: 1;
    color: var(--ink); margin: 0;
}

.gc-hero span { font-size: 1.4rem; font-weight: 600; color: var(--ink-2); }

.gc-meter {
    margin-top: 14px; height: 10px; border-radius: 999px;
    background: var(--track); overflow: hidden;
}

.gc-meter-fill {
    height: 100%; background: var(--accent);
    border-radius: 0 4px 4px 0;
    transition: width .7s cubic-bezier(.2,.8,.2,1);
}

/* probability bars - single measure, one hue, value at the tip */
.gc-probs-title {
    font-size: 0.8rem; font-weight: 600; letter-spacing: 0.05em;
    text-transform: uppercase; color: var(--ink-2); margin: 0 0 18px;
}

.gc-prob { display: flex; align-items: center; gap: 14px; margin-bottom: 14px; }
.gc-prob:last-child { margin-bottom: 0; }

.gc-prob-name {
    width: 96px; flex-shrink: 0; text-align: right;
    font-size: 0.9rem; color: var(--ink-2); text-transform: capitalize;
}

.gc-prob.is-top .gc-prob-name { color: var(--ink); font-weight: 650; }

.gc-prob-track {
    display: block;                 /* spans are inline: width/height would be ignored */
    flex: 1; height: 14px; border-radius: 999px;
    background: var(--track); overflow: hidden;
}

.gc-prob-fill {
    display: block;
    height: 100%; min-width: 3px; background: var(--accent-soft);
    border-radius: 0 4px 4px 0;
    transition: width .7s cubic-bezier(.2,.8,.2,1);
}

.gc-prob.is-top .gc-prob-fill { background: var(--accent); }

.gc-prob-val {
    width: 60px; flex-shrink: 0;
    font-size: 0.86rem; color: var(--ink-2);
    font-variant-numeric: tabular-nums;
}

.gc-prob.is-top .gc-prob-val { color: var(--ink); font-weight: 650; }

.gc-note {
    display: flex; gap: 10px; align-items: flex-start;
    margin: 0 28px 28px; padding: 14px 16px;
    background: #f4f4f1; border-left: 3px solid var(--rule);
    border-radius: var(--r-sm);
    font-size: 0.83rem; color: var(--ink-2); line-height: 1.6;
}

.gc-note i { color: var(--ink-muted); margin-top: 2px; }

.gc-errors {
    margin-top: 24px; padding: 16px 18px; border-radius: var(--r-md);
    background: #fdeaea; border: 1px solid rgba(208,59,59,0.35); color: #8d2020;
    font-size: 0.92rem;
}

.gc-errors p { margin: 0 0 4px; }
.gc-errors p:last-child { margin: 0; }

.gc-login-cta {
    display: flex; align-items: center; gap: 12px;
    padding: 16px 18px; border-radius: var(--r-md); margin-bottom: 26px;
    background: #fdeaea; border: 1px solid rgba(208,59,59,0.35); color: #8d2020;
    font-size: 0.95rem;
}

.gc-login-cta a { color: #8d2020; font-weight: 700; text-decoration: underline; }

/* ---------- responsive ---------- */
@media (max-width: 900px) {
    .gc-result { grid-template-columns: 1fr; }
}

@media (max-width: 768px) {
    .gc-root { padding: 32px 14px 48px; }
    .gc-viewer { grid-template-columns: 1fr; gap: 18px; padding: 18px; }
    .gc-card-body, .gc-result { padding: 18px; }
    .gc-card-head { padding: 16px 18px; }
    .gc-alert, .gc-note { margin-left: 18px; margin-right: 18px; }
    .gc-btn { width: 100%; justify-content: center; }
    .gc-hero { font-size: 2.4rem; }
    .gc-prob-name { width: 74px; font-size: 0.8rem; }
    .gc-prob-val { width: 50px; font-size: 0.78rem; }
}
</style>
</head>
<body>
<div class="gc-root">
  <div class="gc-shell">

    <div class="gc-head">
        <span class="gc-eyebrow"><i class="fas fa-brain"></i> Explainable AI</span>
        <h1 class="gc-title">Grad-CAM Analysis</h1>
        <p class="gc-sub">
            See <strong>where</strong> the model looked when it made its call &mdash;
            the scan and the activation heatmap, side by side.
        </p>
    </div>

    @if(session('error'))
        <div class="gc-login-cta">
            <i class="fas fa-circle-exclamation"></i>
            <div>{{ session('error') }} <a href="{{ route('login') }}">Log in</a> to proceed.</div>
        </div>
    @endif

    <!-- ================= upload ================= -->
    <div class="gc-card">
        <div class="gc-card-head">
            <h2 class="gc-card-title"><i class="fas fa-cloud-arrow-up"></i> New analysis</h2>
            <span class="gc-badge">JPG &middot; PNG &middot; max 5 MB</span>
        </div>
        <div class="gc-card-body">
            <form action="{{ route('gradcam.predict') }}" method="POST" enctype="multipart/form-data" id="gcForm">
                @csrf
                <div class="row g-4">
                    <div class="col-lg-8">
                        <label class="gc-field-label" for="image">MRI scan</label>
                        <label class="gc-drop" id="gcDrop" for="image">
                            <input type="file" name="image" id="image" accept="image/*" required>
                            <i class="fas fa-file-medical"></i>
                            <span class="gc-drop-main">Click to choose a scan</span>
                            <span class="gc-drop-hint" id="gcDropHint">or drag and drop it here</span>
                        </label>
                    </div>
                    <div class="col-lg-4">
                        <label class="gc-field-label" for="model">Model</label>
                        <select name="model" id="model" class="gc-select">
                            <option value="1">Model 1 &mdash; presys</option>
                            <option value="2">Model 2 &mdash; 1sys</option>
                        </select>
                        <p class="gc-drop-hint" style="margin-top:10px;">
                            Two independently trained classifiers. Run both to compare.
                        </p>
                    </div>
                </div>

                <div class="gc-actions">
                    <button type="submit" class="gc-btn" id="gcSubmit">
                        <i class="fas fa-wand-magic-sparkles gc-idle"></i>
                        <i class="fas fa-circle-notch gc-spin"></i>
                        <span>Generate Grad-CAM</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= result ================= -->
    @if(session('result') && session('imageUrl'))
        @php
            $result   = session('result');
            $probs    = $result['probabilities'] ?? [];
            $topClass = $result['prediction'] ?? null;
            $conf     = $result['confidence'] ?? 0;
            $baseUrl  = session('baseUrl');
            $gradUrl  = session('gradcamUrl');
        @endphp

        <div class="gc-card">
            <div class="gc-card-head">
                <h2 class="gc-card-title"><i class="fas fa-microscope"></i> Analysis result</h2>
                <div class="gc-badges">
                    <span class="gc-badge is-accent"><i class="fas fa-layer-group"></i> {{ session('modelUsed') }}</span>
                    @if(!empty($result['gradcam_layer']))
                        <span class="gc-badge">layer {{ $result['gradcam_layer'] }}</span>
                    @endif
                </div>
            </div>

            @if(empty($result['is_mri']))
                <div class="gc-alert" style="margin-top:20px;">
                    <i class="fas fa-triangle-exclamation"></i>
                    <div>
                        <strong>Non-MRI image detected</strong> ({{ $result['mri_confidence'] ?? '' }}% confidence).
                        The heatmap is still shown, but this classification should not be relied on.
                    </div>
                </div>
            @endif

            <!-- viewer -->
            <div class="gc-viewer">
                <div class="gc-pane">
                    <div class="gc-pane-bar">
                        <span class="gc-pane-name">Uploaded scan</span>
                        <span class="gc-pane-tag">as submitted</span>
                    </div>
                    <div class="gc-frame">
                        <img src="{{ session('imageUrl') }}" alt="Uploaded MRI scan">
                    </div>
                    <div class="gc-pane-foot">
                        <p class="gc-legend-cap" style="margin:0;">Original image, unmodified.</p>
                    </div>
                </div>

                <div class="gc-pane">
                    <div class="gc-pane-bar">
                        <span class="gc-pane-name">Grad-CAM</span>
                        <span class="gc-pane-tag">{{ $result['gradcam_layer'] ?? 'Conv5' }} activations</span>
                    </div>
                    <div class="gc-frame">
                        @if($baseUrl)
                            <img src="{{ $baseUrl }}" alt="Preprocessed scan">
                        @endif
                        @if($gradUrl)
                            <img src="{{ $gradUrl }}" alt="Grad-CAM heatmap overlay" id="gcOverlay">
                        @endif
                    </div>
                    <div class="gc-pane-foot">
                        @if($baseUrl && $gradUrl)
                            <div class="gc-blend-row">
                                <input type="range" min="0" max="100" value="100" class="gc-range" id="gcBlend"
                                       aria-label="Heatmap opacity">
                                <span class="gc-blend-val" id="gcBlendVal">100%</span>
                            </div>
                            <div class="gc-presets">
                                <button type="button" class="gc-preset" data-v="0">Scan</button>
                                <button type="button" class="gc-preset" data-v="50">Blend</button>
                                <button type="button" class="gc-preset is-on" data-v="100">Heatmap</button>
                            </div>
                        @endif
                        <div class="gc-legend">
                            <span class="gc-legend-cap">Low</span>
                            <span class="gc-legend-bar"></span>
                            <span class="gc-legend-cap">High influence</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- verdict + probabilities -->
            <div class="gc-result" style="padding-top:0;">
                <div class="gc-verdict">
                    <p class="gc-kicker">Predicted class</p>
                    <h3 class="gc-class">{{ $topClass ?? '—' }}</h3>

                    <p class="gc-kicker">Confidence</p>
                    <p class="gc-hero">{{ $conf }}<span>%</span></p>
                    <div class="gc-meter">
                        <div class="gc-meter-fill" style="width: {{ max(min($conf, 100), 0) }}%"></div>
                    </div>
                </div>

                <div>
                    <p class="gc-probs-title">Class probabilities</p>
                    @foreach($probs as $label => $prob)
                        <div class="gc-prob {{ $label === $topClass ? 'is-top' : '' }}">
                            <span class="gc-prob-name">{{ $label }}</span>
                            <span class="gc-prob-track">
                                <span class="gc-prob-fill" style="width: {{ max(min($prob, 100), 0.8) }}%"></span>
                            </span>
                            <span class="gc-prob-val">{{ $prob }}%</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="gc-note">
                <i class="fas fa-circle-info"></i>
                <div>
                    Grad-CAM highlights the regions that most drove the predicted class, read from the
                    last convolutional block. The heatmap is overlaid on the <em>preprocessed</em> scan &mdash;
                    what the model actually sees. This is an explainability aid, not a diagnosis;
                    always consult a medical professional.
                </div>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="gc-errors">
            @foreach ($errors->all() as $error)
                <p><i class="fas fa-circle-exclamation"></i> {{ $error }}</p>
            @endforeach
        </div>
    @endif

  </div>
</div>

<script>
(function () {
    // --- file picker feedback ---
    var input = document.getElementById('image');
    var drop  = document.getElementById('gcDrop');
    var hint  = document.getElementById('gcDropHint');

    if (input && drop && hint) {
        input.addEventListener('change', function () {
            if (input.files && input.files.length) {
                drop.classList.add('has-file');
                hint.textContent = input.files[0].name;
            } else {
                drop.classList.remove('has-file');
                hint.textContent = 'or drag and drop it here';
            }
        });
        ['dragenter', 'dragover'].forEach(function (e) {
            drop.addEventListener(e, function (ev) { ev.preventDefault(); drop.classList.add('is-over'); });
        });
        ['dragleave', 'drop'].forEach(function (e) {
            drop.addEventListener(e, function () { drop.classList.remove('is-over'); });
        });
    }

    // --- busy state while the model runs ---
    var form = document.getElementById('gcForm');
    var btn  = document.getElementById('gcSubmit');
    if (form && btn) {
        form.addEventListener('submit', function () {
            btn.classList.add('is-busy');
            btn.disabled = true;
            btn.querySelector('span').textContent = 'Analysing…';
        });
    }

    // --- heatmap blend ---
    var range   = document.getElementById('gcBlend');
    var overlay = document.getElementById('gcOverlay');
    var readout = document.getElementById('gcBlendVal');
    var presets = document.querySelectorAll('.gc-preset');

    function setBlend(v) {
        if (!overlay) return;
        overlay.style.opacity = v / 100;
        if (readout) readout.textContent = v + '%';
        presets.forEach(function (p) {
            p.classList.toggle('is-on', parseInt(p.dataset.v, 10) === parseInt(v, 10));
        });
    }

    if (range && overlay) {
        range.addEventListener('input', function () { setBlend(this.value); });
        presets.forEach(function (p) {
            p.addEventListener('click', function () {
                range.value = p.dataset.v;
                setBlend(p.dataset.v);
            });
        });
    }

    // --- animate meters in from zero ---
    window.addEventListener('load', function () {
        document.querySelectorAll('.gc-prob-fill, .gc-meter-fill').forEach(function (el) {
            var w = el.style.width;
            el.style.width = '0%';
            requestAnimationFrame(function () {
                requestAnimationFrame(function () { el.style.width = w; });
            });
        });
    });
})();
</script>

<x-footer />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
