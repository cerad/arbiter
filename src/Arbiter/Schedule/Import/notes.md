
    spike - no optimizations
    [filename] => C:\Users\Arthur\Google Drive\arbiter\Fall2015\ALYS-Fall2015-20150814-GamesWithSlots.xml
    [basename] => ALYS-Fall2015-20150814-GamesWithSlots.xml
    [message] => 
    [countGamesTotal] => 1503
    [memory] => 2883584
    [duration] => 303922
    
    spike initial load added unique indexes
    ALYS-Fall2015-20150814-GamesWithSlots.xml
    [countGamesTotal] => 1503
    [memory] => 2883584
    [duration] => 310605 Took a little longer
    
    spike update checking
    ALYS-Fall2015-20150814-GamesWithSlots.xml
    [countGamesTotal] => 1503
    [memory] => 2883584
    [duration] => 5654
    
    giles
    [basename] => NASOA-Fall2015-20150814-GamesWithSlots.xml
    [message] => 
    [countGamesTotal] => 214
    [memory] => 2621440
    [duration] => 38.559    
    
    [basename] => ALYS-Fall2015-20150814-GamesWithSlots.xml
    [message] => 
    [countGamesTotal] => 1503
    [memory] => 2883584
    [duration] => 348.618    
    
### Slots

Should slots be a name, a key or an index?

If they are indexes then they should be globally unique and sortable.
That kind of overloads the semantics but might be worth it in this case.

For teams, there are typically two teams, home and away so the name work well.
But there are times when three or more teams are involved (jamborees).
Small number of indexes.  Always want to sort by home then away.
So any of the approaches would work.

For soccer officials, there will be one official in charge.

Referee - dsc
Referee - solo

Referee - dual here the responsibilities shift a bit but still in charge
Referee 1 - could be specific to duals
Referee 2 - second referee on a dual