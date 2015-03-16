# Election data tester

## Generate data

```
php {{method}}.php candidatesNum votersNum
```

Examples:


```
php random.php 6 10
php increasing.php 2 100
php decreasing.php 6 1000
php silence.php 9 90
```

Generated data will be saved in the `data.txt` file.

## Assess data

The following command will test previously generated election data in various of methods.

```
php tester.php [quiet]
```

Example output:

```
$ php tester.php
B wins in borda; B: 270; D: 268; F: 250; E: 247; C: 241; A: 224;
B wins in plurality; B: 25; D: 19; E: 18; A: 14; C: 14; F: 10;
F wins in veto; F: 88; B: 87; D: 87; E: 81; A: 79; C: 78;
F wins in condorcet; F: 6; D: 6; E: 6; C: 6; B: 6; A: 3;
D wins in STV; D: 1; F: 0; E: 0; A: 0; C: 0; B: 0;
Dataset is GOOD
```

## Batch testing

```
php batch.php {{method}} candidatesNum votersNum repeatsNum
```

Example:

```
$ php batch.php random 6 100 15
1       2
2       5
3       5
4       3
5       0
0.34951744703159
```

First column of resutls is the number of winners in one test (represents diversity of dataset).
Second column is the total number of tests cases that had corresponding number of winners.

Last line in results is the average time of data generation.



