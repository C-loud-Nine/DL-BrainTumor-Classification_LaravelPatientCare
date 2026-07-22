<x-header />

@php
    use Illuminate\Support\Str;

    $total     = $reports->count();
    $confirmed = 0; $disputed = 0; $uncertain = 0;
    foreach ($reports as $r) {
        $v = $verdicts[$r->id]->verdict ?? null;
        if ($v === 'Yes')       $confirmed++;
        elseif ($v === 'No')    $disputed++;
        elseif ($v === 'Uncertain') $uncertain++;
    }
    $pending = $total - $confirmed - $disputed - $uncertain;
@endphp

<div class="rv-root">
  <div class="rv-shell">

    <div class="rv-head">
        <div>
            <span class="rv-eyebrow"><i class="fas fa-user-doctor"></i> Clinical review</span>
            <h1 class="rv-title">Report Review Console</h1>
            <p class="rv-sub">Inspect each scan with Grad-CAM, then record a reasoned verdict.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="rv-flash is-good"><i class="fas fa-circle-check"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="rv-flash is-bad"><i class="fas fa-circle-exclamation"></i> {{ session('error') }}</div>
    @endif

    <!-- ===== stat tiles ===== -->
    <div class="rv-stats">
        <div class="rv-stat">
            <p class="rv-stat-label">Total reports</p>
            <p class="rv-stat-value">{{ $total }}</p>
        </div>
        <div class="rv-stat">
            <p class="rv-stat-label">Awaiting review</p>
            <p class="rv-stat-value">{{ $pending }}</p>
            <span class="rv-stat-note">needs a verdict</span>
        </div>
        <div class="rv-stat">
            <p class="rv-stat-label">Confirmed</p>
            <p class="rv-stat-value">{{ $confirmed }}</p>
            <span class="rv-stat-note">doctor agreed</span>
        </div>
        <div class="rv-stat">
            <p class="rv-stat-label">Disputed</p>
            <p class="rv-stat-value">{{ $disputed }}</p>
            <span class="rv-stat-note">reclassified</span>
        </div>
        <div class="rv-stat">
            <p class="rv-stat-label">Uncertain</p>
            <p class="rv-stat-value">{{ $uncertain }}</p>
            <span class="rv-stat-note">needs follow-up</span>
        </div>
    </div>

    <!-- ===== toolbar ===== -->
    <div class="rv-toolbar">
        <div class="rv-search">
            <i class="fas fa-magnifying-glass"></i>
            <input type="text" id="searchInput" placeholder="Search by patient, scanner, class or ID…">
        </div>
        <select class="rv-select" id="doctorFilter">
            <option value="">All scanners</option>
            <option value="{{ $doctorName }}">My reports ({{ $doctorName }})</option>
        </select>
        <select class="rv-select" id="statusFilter">
            <option value="">Any status</option>
            <option value="Pending">Awaiting review</option>
            <option value="Yes">Confirmed</option>
            <option value="No">Disputed</option>
            <option value="Uncertain">Uncertain</option>
        </select>
    </div>

    <!-- ===== report rows ===== -->
    <div class="rv-list" id="reportsTable">
        @forelse($reports as $report)
            @php
                $v      = $verdicts[$report->id] ?? null;
                $status = $v->verdict ?? 'Pending';
                // Confidence has been stored two ways over the life of this app:
                // older rows as a 0-1 fraction, newer rows as 0-100. Normalise
                // both to a percentage so the column is comparable.
                $conf    = is_numeric($report->confidence) ? (float) $report->confidence : 0;
                $confPct = $conf <= 1 ? $conf * 100 : ($conf > 100 ? $conf / 100 : $conf);
            @endphp
            <article class="rv-row"
                     data-report-id="{{ $report->id }}"
                     data-scanner-name="{{ $report->scanner_name }}"
                     data-status="{{ $status }}"
                     data-image="{{ asset('uploads/mri/'.$report->report_image) }}"
                     data-patient="{{ $report->user_name }}"
                     data-scanner="{{ $report->scanner_name }}"
                     data-class="{{ $report->report_class }}"
                     data-confidence="{{ $report->confidence }}"
                     data-created="{{ \Carbon\Carbon::parse($report->created_at)->format('D, M j Y · g:i A') }}"
                     data-verdict="{{ $v->verdict ?? '' }}"
                     data-corrected="{{ $v->corrected_class ?? '' }}"
                     data-certainty="{{ $v->certainty ?? '' }}"
                     data-notes="{{ $v->notes ?? '' }}"
                     data-reviewer="{{ $v->reviewed_by ?? '' }}">

                <div class="rv-thumb">
                    <img src="{{ asset('uploads/mri/'.$report->report_image) }}" alt="Scan for report {{ $report->id }}" loading="lazy">
                </div>

                <div class="rv-row-main">
                    <div class="rv-row-top">
                        <span class="rv-id">#{{ $report->id }}</span>
                        <h3 class="rv-patient">{{ $report->user_name }}</h3>
                        <span class="rv-chip rv-chip-{{ Str::slug($status) }}">
                            <i class="fas {{ $status === 'Yes' ? 'fa-circle-check' : ($status === 'No' ? 'fa-circle-xmark' : ($status === 'Uncertain' ? 'fa-circle-question' : 'fa-clock')) }}"></i>
                            {{ $status === 'Yes' ? 'Confirmed' : ($status === 'No' ? 'Disputed' : ($status === 'Uncertain' ? 'Uncertain' : 'Awaiting review')) }}
                        </span>
                    </div>

                    <div class="rv-meta">
                        <span><i class="fas fa-user-doctor"></i> {{ $report->scanner_name }}</span>
                        <span><i class="fas fa-calendar"></i> {{ \Carbon\Carbon::parse($report->created_at)->format('M j, Y · g:i A') }}</span>
                    </div>

                    <div class="rv-pred">
                        <span class="rv-pred-label">Model call</span>
                        <span class="rv-pred-class">{{ $report->report_class }}</span>
                        <span class="rv-meter" title="{{ $report->confidence }}">
                            <span class="rv-meter-fill" style="width: {{ max(min($confPct,100),0) }}%"></span>
                        </span>
                        <span class="rv-pred-val">{{ number_format($confPct, 2) }}%</span>
                    </div>

                    @if($v && $v->verdict === 'No' && $v->corrected_class)
                        <p class="rv-corrected">
                            <i class="fas fa-arrow-right-arrow-left"></i>
                            Reclassified as <strong>{{ $v->corrected_class }}</strong>
                            @if($v->reviewed_by) by {{ $v->reviewed_by }} @endif
                        </p>
                    @endif
                </div>

                <div class="rv-row-action">
                    <button type="button" class="rv-btn rv-open">
                        <i class="fas fa-microscope"></i> Review
                    </button>
                </div>
            </article>
        @empty
            <div class="rv-empty"><i class="fas fa-inbox"></i><p>No reports yet.</p></div>
        @endforelse
    </div>

    <div class="rv-empty" id="noResults" style="display:none;">
        <i class="fas fa-magnifying-glass"></i><p>No reports match your filters.</p>
    </div>

  </div>
</div>

<!-- ================= review panel ================= -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content rv-modal">

      <div class="rv-modal-head">
        <div>
          <h5 class="rv-modal-title">Report <span id="mReportId">#—</span> · <span id="mPatient">—</span></h5>
          <p class="rv-modal-sub" id="mMeta">—</p>
        </div>
        <button type="button" class="rv-close" data-bs-dismiss="modal" aria-label="Close">
          <i class="fas fa-xmark"></i>
        </button>
      </div>

      <div class="rv-modal-body">

        <!-- ---------- left: imaging ---------- -->
        <div class="rv-col-img">
          <div class="rv-panes">
            <div class="rv-pane">
              <div class="rv-pane-bar">
                <span class="rv-pane-name">Scan</span>
                <span class="rv-pane-tag">as recorded</span>
              </div>
              <div class="rv-frame"><img id="mScan" src="" alt="Report scan"></div>
            </div>

            <div class="rv-pane">
              <div class="rv-pane-bar">
                <span class="rv-pane-name">Grad-CAM</span>
                <span class="rv-pane-tag" id="mLayer">not generated</span>
              </div>
              <div class="rv-frame" id="mCamFrame">
                <img id="mCamBase" src="" alt="Preprocessed scan" style="display:none;">
                <img id="mCamOverlay" src="" alt="Grad-CAM heatmap" style="display:none;">
                <div class="rv-cam-empty" id="mCamEmpty">
                  <i class="fas fa-wand-magic-sparkles"></i>
                  <p>Generate a heatmap to see where the model looked.</p>
                </div>
                <div class="rv-cam-empty" id="mCamBusy" style="display:none;">
                  <i class="fas fa-circle-notch rv-spin"></i>
                  <p>Running model…</p>
                </div>
              </div>
              <div class="rv-pane-foot">
                <div class="rv-cam-controls" id="mCamControls" style="display:none;">
                  <div class="rv-blend-row">
                    <input type="range" min="0" max="100" value="100" class="rv-range" id="mBlend" aria-label="Heatmap opacity">
                    <span class="rv-blend-val" id="mBlendVal">100%</span>
                  </div>
                  <div class="rv-presets">
                    <button type="button" class="rv-preset" data-v="0">Scan</button>
                    <button type="button" class="rv-preset" data-v="50">Blend</button>
                    <button type="button" class="rv-preset is-on" data-v="100">Heatmap</button>
                  </div>
                  <div class="rv-legend">
                    <span class="rv-legend-cap">Low</span>
                    <span class="rv-legend-bar"></span>
                    <span class="rv-legend-cap">High influence</span>
                  </div>
                </div>

                <div class="rv-cam-run">
                  <select class="rv-select rv-select-dark" id="mModel">
                    <option value="1">Model 1 — presys</option>
                    <option value="2">Model 2 — 1sys</option>
                  </select>
                  <button type="button" class="rv-btn rv-btn-sm" id="mRunCam">
                    <i class="fas fa-wand-magic-sparkles"></i> Generate
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="rv-read">
            <p class="rv-read-title">Reading the heatmap</p>
            <p>
              Warm regions (<strong>red / yellow</strong>) are the areas that most drove the model's
              predicted class, read from its last convolutional block. Use the slider to fade between
              the scan and the overlay. Activation on skull, background or scanner artefact rather
              than on tissue is a strong reason to <strong>disagree</strong>.
            </p>
          </div>
        </div>

        <!-- ---------- right: evidence + verdict ---------- -->
        <div class="rv-col-form">

          <section class="rv-block">
            <h6 class="rv-block-title">Recorded result</h6>
            <div class="rv-kv"><span>Predicted class</span><strong id="mClass">—</strong></div>
            <div class="rv-kv"><span>Confidence</span><strong id="mConf">—</strong></div>
            <div class="rv-kv"><span>Scanned by</span><strong id="mScanner">—</strong></div>
            <div class="rv-kv"><span>Recorded</span><strong id="mCreated">—</strong></div>
          </section>

          <section class="rv-block" id="mLiveBlock" style="display:none;">
            <h6 class="rv-block-title">Live model re-run <span class="rv-tag" id="mLiveModel"></span></h6>
            <div class="rv-alert is-warn" id="mNonMri" style="display:none;">
              <i class="fas fa-triangle-exclamation"></i>
              <span>Flagged as a <strong>non-MRI</strong> image (<span id="mMriConf"></span>% confidence).</span>
            </div>
            <div id="mProbs"></div>
            <p class="rv-agree" id="mAgree"></p>
          </section>

          <section class="rv-block">
            <h6 class="rv-block-title">Your verdict</h6>
            <form method="POST" id="verdictForm" action="{{ route('saveVerdict') }}">
              @csrf
              <input type="hidden" name="report_id" id="reportIdInput">
              <input type="hidden" name="verdict" id="verdictInput">

              <div class="rv-choices">
                <button type="button" class="rv-choice is-yes" data-v="Yes">
                  <i class="fas fa-circle-check"></i>
                  <span class="rv-choice-t">Agree</span>
                  <span class="rv-choice-d">Model call is correct</span>
                </button>
                <button type="button" class="rv-choice is-no" data-v="No">
                  <i class="fas fa-circle-xmark"></i>
                  <span class="rv-choice-t">Disagree</span>
                  <span class="rv-choice-d">Needs reclassifying</span>
                </button>
                <button type="button" class="rv-choice is-unc" data-v="Uncertain">
                  <i class="fas fa-circle-question"></i>
                  <span class="rv-choice-t">Uncertain</span>
                  <span class="rv-choice-d">Needs follow-up</span>
                </button>
              </div>

              <div class="rv-field" id="correctedWrap" style="display:none;">
                <label class="rv-label" for="correctedClass">Correct class</label>
                <select class="rv-select rv-select-full" name="corrected_class" id="correctedClass">
                  <option value="">Select the correct class…</option>
                  <option value="glioma">Glioma</option>
                  <option value="meningioma">Meningioma</option>
                  <option value="pituitary">Pituitary</option>
                  <option value="notumor">No tumour</option>
                </select>
              </div>

              <div class="rv-field">
                <label class="rv-label" for="certainty">How certain are you?</label>
                <div class="rv-segment" id="certaintySeg">
                  <button type="button" data-v="High">High</button>
                  <button type="button" data-v="Moderate">Moderate</button>
                  <button type="button" data-v="Low">Low</button>
                </div>
                <input type="hidden" name="certainty" id="certaintyInput">
              </div>

              <div class="rv-field">
                <label class="rv-label" for="notes">Clinical notes <span class="rv-optional">optional</span></label>
                <textarea class="rv-textarea" name="notes" id="notes" rows="3"
                          maxlength="2000" placeholder="Observations, differentials, recommended follow-up…"></textarea>
              </div>

              <p class="rv-prev" id="mPrev" style="display:none;"></p>

              <button type="submit" class="rv-btn rv-btn-block" id="saveVerdictBtn" disabled>
                <i class="fas fa-floppy-disk"></i> Save verdict
              </button>
            </form>
          </section>

        </div>
      </div>
    </div>
  </div>
</div>

<x-footer />
@include('admin.script')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('reviewModal');
    var modal   = new bootstrap.Modal(modalEl);
    var current = null;

    var $ = function (id) { return document.getElementById(id); };

    // ---------------- open review panel ----------------
    document.querySelectorAll('.rv-row').forEach(function (row) {
        row.querySelector('.rv-open').addEventListener('click', function () {
            current = row;
            var d = row.dataset;

            $('mReportId').textContent = '#' + d.reportId;
            $('mPatient').textContent  = d.patient;
            $('mMeta').textContent     = d.scanner + ' · ' + d.created;
            $('mScan').src             = d.image;
            $('mClass').textContent    = d.class || '—';

            // Match the list's normalisation: 0-1 fractions and 0-100 both occur.
            var c = parseFloat(d.confidence);
            if (!isNaN(c)) c = c <= 1 ? c * 100 : (c > 100 ? c / 100 : c);
            $('mConf').textContent    = isNaN(c) ? '—' : c.toFixed(2) + '%';
            $('mScanner').textContent = d.scanner;
            $('mCreated').textContent = d.created;
            $('reportIdInput').value  = d.reportId;

            resetCam();
            resetForm(d);
            modal.show();
        });
    });

    // ---------------- verdict choices ----------------
    var choices = document.querySelectorAll('.rv-choice');
    choices.forEach(function (b) {
        b.addEventListener('click', function () {
            choices.forEach(function (x) { x.classList.remove('is-on'); });
            b.classList.add('is-on');
            $('verdictInput').value = b.dataset.v;
            $('correctedWrap').style.display = (b.dataset.v === 'No') ? 'block' : 'none';
            validate();
        });
    });

    var segBtns = document.querySelectorAll('#certaintySeg button');
    segBtns.forEach(function (b) {
        b.addEventListener('click', function () {
            segBtns.forEach(function (x) { x.classList.remove('is-on'); });
            b.classList.add('is-on');
            $('certaintyInput').value = b.dataset.v;
        });
    });

    $('correctedClass').addEventListener('change', validate);

    function validate() {
        var v = $('verdictInput').value;
        var ok = !!v;
        // Disagreeing without saying what it should be isn't a usable review.
        if (v === 'No' && !$('correctedClass').value) ok = false;
        $('saveVerdictBtn').disabled = !ok;
    }

    function resetForm(d) {
        choices.forEach(function (x) { x.classList.remove('is-on'); });
        segBtns.forEach(function (x) { x.classList.remove('is-on'); });
        $('verdictInput').value    = '';
        $('certaintyInput').value  = '';
        $('correctedClass').value  = '';
        $('notes').value           = '';
        $('correctedWrap').style.display = 'none';

        // Pre-fill from an existing review so edits start from what was recorded.
        if (d.verdict) {
            var btn = document.querySelector('.rv-choice[data-v="' + d.verdict + '"]');
            if (btn) { btn.classList.add('is-on'); $('verdictInput').value = d.verdict; }
            if (d.verdict === 'No') $('correctedWrap').style.display = 'block';
        }
        if (d.corrected) $('correctedClass').value = d.corrected;
        if (d.certainty) {
            var sb = document.querySelector('#certaintySeg button[data-v="' + d.certainty + '"]');
            if (sb) { sb.classList.add('is-on'); $('certaintyInput').value = d.certainty; }
        }
        if (d.notes) $('notes').value = d.notes;

        var prev = $('mPrev');
        if (d.verdict) {
            prev.style.display = 'block';
            prev.innerHTML = '<i class="fas fa-clock-rotate-left"></i> Previously reviewed'
                + (d.reviewer ? ' by <strong>' + d.reviewer + '</strong>' : '')
                + ' — saving will update it.';
        } else {
            prev.style.display = 'none';
        }
        validate();
    }

    // ---------------- grad-cam ----------------
    function resetCam() {
        $('mCamBase').style.display    = 'none';
        $('mCamOverlay').style.display = 'none';
        $('mCamEmpty').style.display   = 'flex';
        $('mCamBusy').style.display    = 'none';
        $('mCamControls').style.display = 'none';
        $('mLiveBlock').style.display  = 'none';
        $('mNonMri').style.display     = 'none';
        $('mLayer').textContent        = 'not generated';
        $('mProbs').innerHTML          = '';
        $('mAgree').textContent        = '';
    }

    $('mRunCam').addEventListener('click', function () {
        if (!current) return;
        var token = document.querySelector('meta[name="csrf-token"]');
        var body  = new FormData();
        body.append('report_id', current.dataset.reportId);
        body.append('model', $('mModel').value);
        if (token) body.append('_token', token.getAttribute('content'));

        $('mCamEmpty').style.display = 'none';
        $('mCamBusy').style.display  = 'flex';
        this.disabled = true;

        fetch("{{ route('docreport.gradcam') }}", {
            method: 'POST',
            body: body,
            headers: token ? { 'X-CSRF-TOKEN': token.getAttribute('content') } : {},
            credentials: 'same-origin'
        })
        .then(function (r) { return r.json(); })
        .then(function (data) {
            $('mRunCam').disabled = false;
            $('mCamBusy').style.display = 'none';

            if (!data || data.error) {
                $('mCamEmpty').style.display = 'flex';
                $('mCamEmpty').innerHTML =
                    '<i class="fas fa-triangle-exclamation"></i><p>' +
                    ((data && data.error) || 'Could not generate the heatmap.') + '</p>';
                return;
            }

            if (data.baseUrl)    { $('mCamBase').src = data.baseUrl;      $('mCamBase').style.display = 'block'; }
            if (data.gradcamUrl) { $('mCamOverlay').src = data.gradcamUrl; $('mCamOverlay').style.display = 'block'; }
            $('mCamControls').style.display = 'block';
            $('mLayer').textContent = (data.gradcam_layer || 'Conv5') + ' activations';
            setBlend(100);

            // live evidence panel
            $('mLiveBlock').style.display = 'block';
            $('mLiveModel').textContent   = data.model || '';

            if (data.is_mri === false) {
                $('mNonMri').style.display = 'flex';
                $('mMriConf').textContent  = data.mri_confidence;
            }

            var probs = data.probabilities || {};
            var top   = data.prediction;
            var html  = '';
            Object.keys(probs).forEach(function (k) {
                var p = probs[k];
                html += '<div class="rv-prob ' + (k === top ? 'is-top' : '') + '">' +
                        '<span class="rv-prob-name">' + k + '</span>' +
                        '<span class="rv-prob-track"><span class="rv-prob-fill" style="width:' + Math.max(Math.min(p,100),0.8) + '%"></span></span>' +
                        '<span class="rv-prob-val">' + p + '%</span></div>';
            });
            $('mProbs').innerHTML = html;

            // does the re-run agree with what was recorded?
            var recorded = (current.dataset.class || '').toLowerCase().trim();
            if (top && recorded) {
                var same = recorded.indexOf(top.toLowerCase()) !== -1;
                $('mAgree').className = 'rv-agree ' + (same ? 'is-match' : 'is-diff');
                $('mAgree').innerHTML = same
                    ? '<i class="fas fa-check"></i> Re-run matches the recorded class (' + top + ', ' + data.confidence + '%).'
                    : '<i class="fas fa-triangle-exclamation"></i> Re-run says <strong>' + top + '</strong> (' + data.confidence + '%), recorded as <strong>' + current.dataset.class + '</strong>.';
            }
        })
        .catch(function () {
            $('mRunCam').disabled = false;
            $('mCamBusy').style.display  = 'none';
            $('mCamEmpty').style.display = 'flex';
            $('mCamEmpty').innerHTML = '<i class="fas fa-triangle-exclamation"></i><p>Could not reach the model server.</p>';
        });
    });

    function setBlend(v) {
        var o = $('mCamOverlay');
        if (o) o.style.opacity = v / 100;
        $('mBlendVal').textContent = v + '%';
        $('mBlend').value = v;
        document.querySelectorAll('.rv-preset').forEach(function (p) {
            p.classList.toggle('is-on', parseInt(p.dataset.v, 10) === parseInt(v, 10));
        });
    }

    $('mBlend').addEventListener('input', function () { setBlend(this.value); });
    document.querySelectorAll('.rv-preset').forEach(function (p) {
        p.addEventListener('click', function () { setBlend(parseInt(p.dataset.v, 10)); });
    });

    // ---------------- filters ----------------
    function applyFilters() {
        var q      = ($('searchInput').value || '').toLowerCase();
        var doc    = $('doctorFilter').value;
        var status = $('statusFilter').value;
        var shown  = 0;

        document.querySelectorAll('.rv-row').forEach(function (row) {
            var okText   = row.textContent.toLowerCase().indexOf(q) !== -1;
            var okDoc    = !doc || row.dataset.scannerName === doc;
            var okStatus = !status || row.dataset.status === status;
            var show     = okText && okDoc && okStatus;
            row.style.display = show ? '' : 'none';
            if (show) shown++;
        });

        $('noResults').style.display = shown === 0 ? 'flex' : 'none';
    }

    ['searchInput', 'doctorFilter', 'statusFilter'].forEach(function (id) {
        $(id).addEventListener(id === 'searchInput' ? 'input' : 'change', applyFilters);
    });
});
</script>

<style>
.rv-root {
    --plane:#f9f9f7; --surface:#fcfcfb; --ink:#0b0b0b; --ink-2:#52514e; --ink-muted:#898781;
    --hairline:rgba(11,11,11,0.10); --rule:#e1e0d9;
    --accent:#2a78d6; --accent-soft:#86b6ef; --track:#cde2fb;
    --good:#0ca30c; --warning:#fab219; --critical:#d03b3b;
    --viewer:#0d0d0d;
    font-family:system-ui,-apple-system,"Segoe UI",sans-serif;
    color:var(--ink); background:var(--plane); padding:48px 24px 72px;
}
.rv-shell{max-width:1180px;margin:0 auto;}

.rv-head{margin-bottom:28px;}
.rv-eyebrow{display:inline-flex;align-items:center;gap:8px;font-size:.72rem;font-weight:600;
    letter-spacing:.08em;text-transform:uppercase;color:var(--accent);
    background:rgba(42,120,214,.08);border:1px solid rgba(42,120,214,.18);
    padding:5px 13px;border-radius:999px;margin-bottom:14px;}
.rv-title{font-size:clamp(1.7rem,3.4vw,2.3rem);font-weight:700;letter-spacing:-.02em;margin:0 0 8px;}
.rv-sub{color:var(--ink-2);margin:0;font-size:.98rem;}

.rv-flash{display:flex;align-items:center;gap:10px;padding:13px 16px;border-radius:12px;
    margin-bottom:20px;font-size:.93rem;}
.rv-flash.is-good{background:#e9f7e9;border:1px solid rgba(12,163,12,.32);color:#0a5c0a;}
.rv-flash.is-bad{background:#fdeaea;border:1px solid rgba(208,59,59,.35);color:#8d2020;}

/* stat tiles */
.rv-stats{display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:26px;}
.rv-stat{background:var(--surface);border:1px solid var(--hairline);border-radius:14px;padding:18px 18px 16px;
    box-shadow:0 1px 2px rgba(11,11,11,.04);}
.rv-stat-label{font-size:.78rem;color:var(--ink-2);margin:0 0 6px;}
.rv-stat-value{font-size:2rem;font-weight:700;line-height:1;margin:0;color:var(--ink);}
.rv-stat-note{font-size:.72rem;color:var(--ink-muted);}

/* toolbar */
.rv-toolbar{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:20px;}
.rv-search{position:relative;flex:1;min-width:240px;}
.rv-search i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:var(--ink-muted);font-size:.85rem;}
.rv-search input{width:100%;padding:12px 14px 12px 38px;font-size:.93rem;font-family:inherit;
    border:1px solid #d7d6ce;border-radius:12px;background:#fff;color:var(--ink);}
.rv-select{padding:12px 14px;font-size:.93rem;font-family:inherit;border:1px solid #d7d6ce;
    border-radius:12px;background:#fff;color:var(--ink);cursor:pointer;min-width:170px;}
.rv-select-full{width:100%;}
.rv-search input:focus,.rv-select:focus,.rv-textarea:focus{outline:none;border-color:var(--accent);
    box-shadow:0 0 0 3px rgba(42,120,214,.16);}

/* rows */
.rv-list{display:flex;flex-direction:column;gap:12px;}
.rv-row{display:flex;align-items:center;gap:18px;background:var(--surface);
    border:1px solid var(--hairline);border-radius:14px;padding:14px 18px;
    box-shadow:0 1px 2px rgba(11,11,11,.04);transition:box-shadow .18s ease,transform .12s ease;}
.rv-row:hover{box-shadow:0 4px 16px rgba(11,11,11,.09);transform:translateY(-1px);}
.rv-thumb{flex-shrink:0;width:78px;height:78px;border-radius:10px;overflow:hidden;background:#000;}
.rv-thumb img{width:100%;height:100%;object-fit:cover;display:block;}
.rv-row-main{flex:1;min-width:0;}
.rv-row-top{display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:6px;}
.rv-id{font-size:.75rem;color:var(--ink-muted);font-variant-numeric:tabular-nums;}
.rv-patient{font-size:1.02rem;font-weight:650;margin:0;}
.rv-chip{display:inline-flex;align-items:center;gap:5px;font-size:.72rem;font-weight:600;
    padding:4px 10px;border-radius:999px;border:1px solid transparent;}
.rv-chip-pending{background:#f1f0ec;color:#5c5b56;border-color:var(--hairline);}
.rv-chip-yes{background:#e9f7e9;color:#0a5c0a;border-color:rgba(12,163,12,.3);}
.rv-chip-no{background:#fdeaea;color:#8d2020;border-color:rgba(208,59,59,.32);}
.rv-chip-uncertain{background:#fff5e0;color:#6b4c00;border-color:rgba(250,178,25,.42);}
.rv-meta{display:flex;gap:16px;flex-wrap:wrap;font-size:.82rem;color:var(--ink-2);margin-bottom:9px;}
.rv-meta i{color:var(--ink-muted);margin-right:4px;}
.rv-pred{display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
.rv-pred-label{font-size:.72rem;text-transform:uppercase;letter-spacing:.05em;color:var(--ink-muted);}
.rv-pred-class{font-size:.88rem;font-weight:650;text-transform:capitalize;}
.rv-meter{display:block;width:150px;height:8px;border-radius:999px;background:var(--track);overflow:hidden;}
.rv-meter-fill{display:block;height:100%;background:var(--accent);border-radius:0 4px 4px 0;}
.rv-pred-val{font-size:.8rem;color:var(--ink-2);font-variant-numeric:tabular-nums;}
.rv-corrected{margin:9px 0 0;font-size:.82rem;color:#8d2020;}
.rv-row-action{flex-shrink:0;}

.rv-btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;padding:11px 20px;
    font-size:.9rem;font-weight:600;font-family:inherit;color:#fff;background:var(--accent);
    border:none;border-radius:11px;cursor:pointer;box-shadow:0 2px 8px rgba(42,120,214,.26);
    transition:background .18s ease,box-shadow .18s ease;}
.rv-btn:hover{background:#256abf;}
.rv-btn[disabled]{opacity:.5;cursor:not-allowed;box-shadow:none;}
.rv-btn-sm{padding:9px 14px;font-size:.83rem;}
.rv-btn-block{width:100%;margin-top:6px;padding:13px;}

.rv-empty{display:flex;flex-direction:column;align-items:center;gap:10px;padding:52px 20px;
    color:var(--ink-muted);background:var(--surface);border:1px dashed var(--rule);border-radius:14px;}
.rv-empty i{font-size:1.7rem;}
.rv-empty p{margin:0;}

/* ---------- modal ---------- */
.rv-modal{border:none;border-radius:18px;overflow:hidden;
    font-family:system-ui,-apple-system,"Segoe UI",sans-serif;
    --accent:#2a78d6;--accent-soft:#86b6ef;--track:#cde2fb;
    --ink:#0b0b0b;--ink-2:#52514e;--ink-muted:#898781;--hairline:rgba(11,11,11,.10);--viewer:#0d0d0d;}
.rv-modal-head{display:flex;align-items:flex-start;justify-content:space-between;gap:16px;
    padding:20px 24px;border-bottom:1px solid var(--hairline);background:#fcfcfb;}
.rv-modal-title{font-size:1.05rem;font-weight:650;margin:0 0 3px;color:var(--ink);}
.rv-modal-sub{font-size:.83rem;color:var(--ink-2);margin:0;}
.rv-close{background:transparent;border:none;font-size:1.15rem;color:var(--ink-muted);cursor:pointer;
    padding:4px 8px;border-radius:8px;}
.rv-close:hover{background:#f1f0ec;color:var(--ink);}
.rv-modal-body{display:grid;grid-template-columns:1.15fr .85fr;gap:0;background:#fcfcfb;}
.rv-col-img{padding:22px;border-right:1px solid var(--hairline);
    display:flex;flex-direction:column;justify-content:center;gap:14px;}
.rv-read{background:#fff;border:1px solid var(--hairline);border-radius:12px;padding:14px 16px;}
.rv-read-title{font-size:.74rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;
    color:var(--ink-2);margin:0 0 8px;}
.rv-read p{margin:0;font-size:.8rem;line-height:1.6;color:var(--ink-2);}
.rv-read strong{color:var(--ink);}
.rv-col-form{padding:22px;display:flex;flex-direction:column;gap:18px;}

.rv-panes{display:grid;grid-template-columns:1fr 1fr;gap:14px;align-items:start;}
.rv-pane{background:var(--viewer);border:1px solid rgba(255,255,255,.08);border-radius:12px;overflow:hidden;}
.rv-pane-bar{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:9px 12px;
    background:rgba(255,255,255,.05);border-bottom:1px solid rgba(255,255,255,.08);}
.rv-pane-name{font-size:.7rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:#e8e8e4;}
.rv-pane-tag{font-size:.64rem;color:#9b9a93;}
.rv-frame{position:relative;width:100%;aspect-ratio:1/1;background:#000;}
.rv-frame img{position:absolute;inset:0;width:100%;height:100%;object-fit:contain;display:block;}
#mCamOverlay{transition:opacity .12s linear;}
.rv-cam-empty{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;
    justify-content:center;gap:8px;padding:16px;text-align:center;color:#8a8983;}
.rv-cam-empty i{font-size:1.4rem;}
.rv-cam-empty p{margin:0;font-size:.76rem;line-height:1.45;}
.rv-spin{animation:rv-rot .8s linear infinite;}
@keyframes rv-rot{to{transform:rotate(360deg);}}
.rv-pane-foot{padding:11px 12px 13px;}
.rv-blend-row{display:flex;align-items:center;gap:9px;}
.rv-range{-webkit-appearance:none;appearance:none;flex:1;height:5px;border-radius:999px;cursor:pointer;
    background:linear-gradient(to right,#6b6b66,var(--accent));}
.rv-range::-webkit-slider-thumb{-webkit-appearance:none;appearance:none;width:15px;height:15px;
    border-radius:50%;background:#fff;border:3px solid var(--accent);}
.rv-range::-moz-range-thumb{width:15px;height:15px;border-radius:50%;background:#fff;border:3px solid var(--accent);}
.rv-blend-val{font-size:.68rem;color:#b8b7b0;width:44px;text-align:right;font-variant-numeric:tabular-nums;}
.rv-presets{display:flex;gap:5px;margin-top:9px;}
.rv-preset{flex:1;padding:6px 3px;font-size:.68rem;font-weight:600;font-family:inherit;color:#cfcec8;
    background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.1);border-radius:7px;cursor:pointer;}
.rv-preset:hover{background:rgba(255,255,255,.14);color:#fff;}
.rv-preset.is-on{background:var(--accent);border-color:var(--accent);color:#fff;}
.rv-legend{display:flex;align-items:center;gap:8px;margin-top:11px;}
.rv-legend-bar{flex:1;height:7px;border-radius:999px;
    background:linear-gradient(to right,#00007f,#0000ff,#007fff,#00ffff,#7fff7f,#ffff00,#ff7f00,#ff0000,#7f0000);}
.rv-legend-cap{font-size:.63rem;color:#9b9a93;white-space:nowrap;}
.rv-cam-run{display:flex;gap:8px;margin-top:11px;}
.rv-select-dark{flex:1;min-width:0;padding:9px 10px;font-size:.78rem;border-radius:8px;
    background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.14);color:#e8e8e4;}
.rv-select-dark option{background:#1a1a19;color:#e8e8e4;}

/* right column blocks */
.rv-block{background:#fff;border:1px solid var(--hairline);border-radius:13px;padding:18px;}
.rv-block-title{font-size:.76rem;font-weight:600;letter-spacing:.06em;text-transform:uppercase;
    color:var(--ink-2);margin:0 0 14px;display:flex;align-items:center;gap:8px;}
.rv-tag{font-size:.66rem;font-weight:600;padding:2px 8px;border-radius:999px;text-transform:none;
    letter-spacing:0;background:rgba(42,120,214,.1);color:#1c5cab;}
.rv-kv{display:flex;justify-content:space-between;gap:14px;padding:7px 0;font-size:.88rem;
    border-bottom:1px dashed var(--hairline);}
.rv-kv:last-child{border-bottom:none;}
.rv-kv span{color:var(--ink-2);}
.rv-kv strong{color:var(--ink);font-weight:600;text-transform:capitalize;text-align:right;}

.rv-alert{display:flex;align-items:flex-start;gap:9px;padding:11px 13px;border-radius:10px;
    font-size:.82rem;line-height:1.5;margin-bottom:13px;}
.rv-alert.is-warn{background:#fff8e6;border:1px solid rgba(250,178,25,.45);color:#6b4c00;}
.rv-alert i{margin-top:2px;color:#a37200;}

.rv-prob{display:flex;align-items:center;gap:10px;margin-bottom:9px;}
.rv-prob:last-child{margin-bottom:0;}
.rv-prob-name{width:84px;text-align:right;font-size:.8rem;color:var(--ink-2);text-transform:capitalize;}
.rv-prob.is-top .rv-prob-name{color:var(--ink);font-weight:650;}
.rv-prob-track{display:block;flex:1;height:12px;border-radius:999px;background:var(--track);overflow:hidden;}
.rv-prob-fill{display:block;height:100%;min-width:3px;background:var(--accent-soft);border-radius:0 4px 4px 0;}
.rv-prob.is-top .rv-prob-fill{background:var(--accent);}
.rv-prob-val{width:52px;font-size:.78rem;color:var(--ink-2);font-variant-numeric:tabular-nums;}
.rv-prob.is-top .rv-prob-val{color:var(--ink);font-weight:650;}

.rv-agree{margin:13px 0 0;font-size:.82rem;line-height:1.5;padding:9px 12px;border-radius:9px;}
.rv-agree.is-match{background:#e9f7e9;color:#0a5c0a;}
.rv-agree.is-diff{background:#fff8e6;color:#6b4c00;}
.rv-agree i{margin-right:5px;}

/* verdict form */
.rv-choices{display:grid;grid-template-columns:repeat(3,1fr);gap:9px;margin-bottom:16px;}
.rv-choice{display:flex;flex-direction:column;align-items:center;gap:3px;padding:13px 7px;
    background:#fff;border:1.5px solid #ddd; border-radius:11px;cursor:pointer;font-family:inherit;
    transition:border-color .16s ease,background .16s ease,box-shadow .16s ease;}
.rv-choice i{font-size:1.15rem;color:var(--ink-muted);margin-bottom:2px;}
.rv-choice-t{font-size:.85rem;font-weight:650;color:var(--ink);}
.rv-choice-d{font-size:.66rem;color:var(--ink-muted);text-align:center;line-height:1.3;}
.rv-choice:hover{border-color:#bdbcb4;}
.rv-choice.is-yes.is-on{border-color:var(--good);background:#f2fbf2;box-shadow:0 0 0 3px rgba(12,163,12,.12);}
.rv-choice.is-yes.is-on i{color:var(--good);}
.rv-choice.is-no.is-on{border-color:var(--critical);background:#fdf3f3;box-shadow:0 0 0 3px rgba(208,59,59,.12);}
.rv-choice.is-no.is-on i{color:var(--critical);}
.rv-choice.is-unc.is-on{border-color:var(--warning);background:#fffaf0;box-shadow:0 0 0 3px rgba(250,178,25,.16);}
.rv-choice.is-unc.is-on i{color:#a37200;}

.rv-field{margin-bottom:15px;}
.rv-label{display:block;font-size:.78rem;font-weight:600;color:var(--ink-2);margin-bottom:7px;}
.rv-optional{font-weight:400;color:var(--ink-muted);}
.rv-segment{display:flex;gap:6px;}
.rv-segment button{flex:1;padding:9px 6px;font-size:.8rem;font-weight:600;font-family:inherit;
    color:var(--ink-2);background:#fff;border:1.5px solid #ddd;border-radius:9px;cursor:pointer;
    transition:border-color .16s ease,color .16s ease,background .16s ease;}
.rv-segment button:hover{border-color:#bdbcb4;}
.rv-segment button.is-on{border-color:var(--accent);background:rgba(42,120,214,.07);color:#1c5cab;}
.rv-textarea{width:100%;padding:11px 13px;font-size:.88rem;font-family:inherit;line-height:1.55;
    border:1px solid #d7d6ce;border-radius:11px;resize:vertical;color:var(--ink);background:#fff;}
.rv-prev{font-size:.78rem;color:var(--ink-2);background:#f4f4f1;border-left:3px solid var(--rule);
    padding:9px 12px;border-radius:8px;margin:0 0 12px;}
.rv-prev i{margin-right:5px;color:var(--ink-muted);}

/* ---------- responsive ---------- */
@media (max-width:1100px){ .rv-stats{grid-template-columns:repeat(3,1fr);} }
@media (max-width:992px){
    .rv-modal-body{grid-template-columns:1fr;}
    .rv-col-img{border-right:none;border-bottom:1px solid var(--hairline);}
}
@media (max-width:768px){
    .rv-root{padding:28px 14px 48px;}
    .rv-stats{grid-template-columns:repeat(2,1fr);gap:10px;}
    .rv-stat-value{font-size:1.6rem;}
    .rv-row{flex-direction:column;align-items:flex-start;gap:12px;}
    .rv-thumb{width:100%;height:170px;}
    .rv-row-action{width:100%;}
    .rv-btn{width:100%;}
    .rv-panes{grid-template-columns:1fr;}
    .rv-choices{grid-template-columns:1fr;}
    .rv-select{width:100%;}
    .rv-meter{width:110px;}
}
</style>
