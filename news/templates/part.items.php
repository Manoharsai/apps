<?php

$feedId = isset($_['feedid']) ? $_['feedid'] : '';

$itemMapper = new OCA\News\ItemMapper();

$showOnlyUnread = true; // FIXME: get this from the settings db
if($showOnlyUnread){
	$items = $itemMapper->findAllStatus($feedId, OCA\News\StatusFlag::UNREAD);
} else {
	$items = $itemMapper->findAll($feedId);
}

echo '<ul>';
foreach($items as $item) {
	
	if($item->isRead()){
		$newsItemClass = "read";
	} else {
		$newsItemClass = "";
	}
	
	if($item->isImportant()){
		$starClass = 'important';
		$startTitle = $l->t('Mark as unimportant');
	} else {
		$starClass = '';
		$startTitle = $l->t('Mark as important');
	}

	echo '<li class="feed_item ' . $newsItemClass .'" data-id="' . $item->getId() . '" data-feedid="' . $feedId . '">';

		echo '<div class="utils">';
			echo '<ul class="primary_item_utils">';
				echo '<li class="star ' . $starClass . '" title="' . $startTitle . '"></li>';
			echo '</ul>';

			echo '<ul class="secondary_item_utils">';
				echo '<li class="keep_unread">' . $l->t('Keep unread') . '<input type="checkbox" /></li>';
			echo '</ul>';
		echo '</div>';

		echo '<h2 class="item_date"><time class="timeago" datetime="' . 
			date('c', $item->getDate()) . '">' . date('F j, Y, g:i a', $item->getDate()) .  '</time>' . '</h2>';

		echo '<h1 class="item_title"><a target="_blank" href="' . $item->getUrl() . '">' . $item->getTitle() . '</a></h1>';	

		if(trim($item->getAuthor()) == ''){
			$from = $l->t('from') . ' ' . parse_url($item->getUrl(), PHP_URL_HOST);
		} else {
			$from = $l->t('from') . ' ' . $item->getAuthor();
		}
		echo '<h2 class="item_author">' . $from . '</h2>';

		echo '<div class="body">' . $item->getBody() . '</div>';

	echo '</li>';

	}
echo '</ul>';
