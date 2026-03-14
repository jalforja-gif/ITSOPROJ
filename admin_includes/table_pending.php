<?php
include 'db_connect.php';

$sql = "
  SELECT ip.*, c.campus_name, d.department_name
  FROM intellectual_properties ip
  LEFT JOIN campuses c ON ip.campus_id = c.campus_id
  LEFT JOIN departments d ON ip.department_id = d.department_id
  WHERE ip.status = 'Pending'
";
$result = $conn->query($sql);
?>

<div class="table-container">
  <div class="table-header">
    <div>Title</div>
    <div>Classification</div>
    <div>Status</div>
    <div>Campus</div>
    <div>Department</div>
    <div>Action</div>
  </div>

  <div class="table-body">
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php
                $tracking = htmlspecialchars($row['tracking_id'], ENT_QUOTES);
                $suggested = htmlspecialchars($row['suggested_classification'] ?? $row['classification'], ENT_QUOTES);
                $similarity = htmlspecialchars($row['similarity_score'] ?? 'N/A', ENT_QUOTES);
                $readiness = htmlspecialchars($row['readiness_score'] ?? 'N/A', ENT_QUOTES);
            ?>
            <div class='table-row-card'>
                <div data-label='Title'>
                    <strong><?= $row['title'] ?></strong><br>
                    <small class='tracking-id' onclick='copyToClipboard("<?= $tracking ?>", this)'>
                        <ion-icon name="copy-outline" style="font-size: 12px;"></ion-icon>
                        <?= $row['tracking_id'] ?>
                    </small>
                </div>

                <div data-label='Classification'><?= $row['classification'] ?></div>
                <div data-label='Status'>
                    <span class='status-badge status-pending'><?= $row['status'] ?></span>
                </div>
                <div data-label='Campus'><?= $row['campus_name'] ?></div>
                <div data-label='Department'><?= $row['department_name'] ?></div>

                <div data-label='Action'>
                    <!-- Check icon opens modal -->
                    <button type="button" class="accept-btn"
                        onclick='openApprovalModal(
                            <?= json_encode($row['ip_id']) ?>,
                            <?= json_encode($suggested) ?>,
                            <?= json_encode($similarity) ?>,
                            <?= json_encode($readiness) ?>
                        )'>
                        <ion-icon name="checkmark-circle-outline"></ion-icon>
                    </button>

                    <?php if (!empty($row['remarks'])): ?>
                        <p class="remarks">⚠ <?= $row['remarks'] ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class='no-data'>No pending applications</p>
    <?php endif; ?>
  </div>
</div>

<!-- Approval Modal -->
<div id="approvalModal" class="glass-modal-overlay" style="display:none;">
  <div class="glass-modal p-6 relative bg-white rounded-lg shadow-lg w-11/12 max-w-md">
    <span class="close-glass absolute top-2 right-3 text-gray-500 hover:text-gray-700 cursor-pointer" onclick="closeApprovalModal()">&times;</span>
    <div class="glass-modal-body space-y-4">
      <h3 class="text-xl font-bold mb-2">ML Recommendation</h3>
      
      <p><strong>Suggested Classification:</strong> <span id="modal_suggested"></span></p>

      <!-- Similarity -->
      <p><strong>Similarity Score:</strong> <span id="modal_similarity_text">0%</span></p>
      <div class="w-full bg-gray-200 h-3 rounded-full">
        <div id="modal_similarity_bar" class="h-3 rounded-full bg-green-500 w-0 transition-all"></div>
      </div>

      <!-- Readiness -->
      <p><strong>Readiness Score:</strong> <span id="modal_readiness_text">0%</span></p>
      <div class="w-full bg-gray-200 h-3 rounded-full">
        <div id="modal_readiness_bar" class="h-3 rounded-full bg-green-500 w-0 transition-all"></div>
      </div>

      <form id="approveForm" method="POST" action="update_status.php" class="mt-4">
        <input type="hidden" name="ip_id" id="modal_ip_id_pending">
        <input type="hidden" name="new_status" value="Ongoing">
        <input type="hidden" name="source" value="admin_dashboard.php?page=all_applications">
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Continue</button>
      </form>
    </div>
  </div>
</div>

<script>
// Open modal and fill values
function openApprovalModal(ipId, suggested, similarity, readiness) {
    // target the pending modal field specifically
    document.getElementById('modal_ip_id_pending').value = ipId;
    document.getElementById('modal_suggested').innerText = suggested;

    const similarityScore = parseFloat(similarity);
    const readinessScore = parseFloat(readiness);

    // Convert to percentage
    const simPercent = (similarityScore * 100).toFixed(1);
    const readPercent = (readinessScore * 100).toFixed(1);

    // Similarity status & color
    let simStatus = "", simColor = "";
    if (similarityScore >= 0.8) { simStatus = "High similarity – might be duplicate"; simColor = "bg-red-500"; }
    else if (similarityScore >= 0.5) { simStatus = "Moderate similarity – check details"; simColor = "bg-yellow-500"; }
    else { simStatus = "Low similarity – likely unique"; simColor = "bg-green-500"; }

    // Readiness status & color
    let readStatus = "", readColor = "";
    if (readinessScore <= 0.3) { readStatus = "Not ready – review needed"; readColor = "bg-red-500"; }
    else if (readinessScore <= 0.7) { readStatus = "Partially ready – consider revisions"; readColor = "bg-yellow-500"; }
    else { readStatus = "Ready for submission"; readColor = "bg-green-500"; }

    // Update text & bars
    document.getElementById("modal_similarity_text").innerText = `${simPercent}% — ${simStatus}`;
    const simBar = document.getElementById("modal_similarity_bar");
    simBar.style.width = simPercent + "%";
    simBar.className = `h-3 rounded-full transition-all ${simColor}`;

    document.getElementById("modal_readiness_text").innerText = `${readPercent}% — ${readStatus}`;
    const readBar = document.getElementById("modal_readiness_bar");
    readBar.style.width = readPercent + "%";
    readBar.className = `h-3 rounded-full transition-all ${readColor}`;

    document.getElementById('approvalModal').style.display = 'flex';
}

// Close modal
function closeApprovalModal() {
    document.getElementById('approvalModal').style.display = 'none';
}

// Close modal when clicking outside
window.addEventListener('click', function(e) {
    const modal = document.getElementById('approvalModal');
    if (e.target === modal) closeApprovalModal();
});

// Copy tracking ID
function copyToClipboard(text, el) {
    navigator.clipboard.writeText(text).then(() => {
        el.innerHTML = 'Copied!';
        setTimeout(() => { el.innerHTML = text; }, 1000);
    });
}
</script>