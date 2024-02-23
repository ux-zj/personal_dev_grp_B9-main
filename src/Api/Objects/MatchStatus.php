<?php

enum MatchStatus : int
{
    case Complete = 1;
    case Ongoing = 0;
    case Disputed = -1;
    case Intialised = 44;
}