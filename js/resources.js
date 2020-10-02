$(".delete").on("click", function() {
	$(".confirm-delete").attr("id", $(this).attr("id"));
	$(".confirm-delete").on("click", function () {
		location.href = "delete_resource.php?resource_id=" + $(this).attr("id");
	});

	$(".modal").modal({
		backdrop: true,
		keyboard: true,
		focus: true,
		show: true
	});
});