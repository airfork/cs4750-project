<?php
if (!empty($articles)) { ?>
    <h3>Delete Results:</h3>
    <table>
        <thead>
        <tr>
            <th>Title</th>
            <th>Author</th>
            <th>Publish Date</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($articles as $article): ?>
            <?php
            if (strlen(trim($article['title'])) === 0 || strlen(trim($article['ajauthor'])) === 0 || strlen(trim($article['pubDate'])) === 0) {
                continue;
            }
            $title = rawurlencode($article['title']);
            echo "<tr class=\"search-list\" onclick=\"delete_article('{$title}', this)\">";
            ?>
            <td>
                <?php echo $article['title']; ?>
            </td>
            <td>
                <?php echo $article['ajauthor']; ?>
            </td>
            <td>
                <?php echo $article['pubDate']; ?>
            </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php } ?>