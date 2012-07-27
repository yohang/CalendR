### Simple calendar generation

{% highlight php %}
<?php
// Use the factory to get your period
$factory = new CalendR\Calendar;
$month = $factory->getMonth(2012, 01);
?>

<table>
    <?php // Iterate over your month and get weeks ?>
    <?php foreach ($month as $week): ?>
    <tr>
        <?php // Iterate over your month and get days ?>
        <?php foreach ($week as $day): ?>
            <?php //Check days that are out of your month ?>
            <td><?php echo $day ?></td>
        <?php endforeach ?>
    </tr>
    <?php endforeach ?>
</table>
{% endhighlight %}
