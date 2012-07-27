### Events

{% highlight php %}

<?php
$month = $factory->getMonth(2012, 6)

// Retrieve an event collection from the event manager
$events = $factory->getEvents($month)
?>

<?php foreach ($month as $week): ?>
    <h2>Week #<?php echo $week ?></h2>
    <ul>
        <?php // retrieve events for the subperiod (week) ?>
        <?php foreach ($events->find($week) as $event): ?>
            <li><?php echo $event ?></li>
        <?php endforeach ?>
    </ul>
<?php endforeach ?>

{% endhighlight %}
