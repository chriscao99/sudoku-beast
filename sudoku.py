#!/usr/bin/env python3

import sys, cProfile, time
from math import sqrt

grid_len = 81
possible = set()
# validDict = {}
posWithIndex = {}
full_groups = {"row":[],"col":[],"sq":[]}
aGroups = [{i+k for i in range(0,9)} for k in range(0,81,9)] # Columns
aGroups += [{i for i in range(0+k,81,9)} for k in range(0,9)] # Rows
aGroups += [{row*9+col+i+k for i in range(0,3) for k in range(0,27,9)} for row in range(0,9,3) for col in range(0,9,3)] # Squares
# puzzle_list = open("sudoku128.txt").read().split()
row_arith = {pos:pos//9 for pos in range(81)}
col_arith = {pos:pos%9 for pos in range(81)}
sq_arith = {pos:3*(pos//27)+(pos%9)//3 for pos in range(81)}

def generateGrid(string):
    n = int(sqrt(len(string)))
    return [string[i:i+n] for i in range(0,len(string),n)],n

def printGrid(grid_string):
    grid,size = generateGrid(grid_string)
    if size == 0: print("Invalid grid")
    for rin,row in enumerate(grid):
        for index, x in enumerate(row):
            sys.stdout.write( x + " " )
            if (index+1)%3 == 0 and index < 8:
                sys.stdout.write( "| " )
        print()
        if (rin+1)%3 == 0 and rin < 8:
            print("----------------------")

def validate(grid):
    for group in aGroups:
        seen = set()
        for pos in group:
            if grid[pos] in seen: return False
            seen.add(grid[pos])
    return True

def validNext(grid,pos):
    row_n = row_arith[pos]
    col_n = col_arith[pos]
    sq_n = sq_arith[pos]
    row_int = {grid[number] for number in aGroups[row_n]}
    col_int = {grid[number] for number in aGroups[9+col_n]}
    sq_int = {grid[number] for number in aGroups[18+sq_n]}
    return set("123456789") - (row_int | col_int | sq_int)

def selectCell(validDict):
    global possible
    if possible:
        return min(possible, key=lambda p: len(validDict[p]))
    return -1

def remove(index, c, validDict):
    for pos in posWithIndex[index]:
        if pos in validDict and c in validDict[pos]:
            validDict[pos].remove(c)

def deduct(grid,validDict):
    global possible,aGroups
    added = {}
    pg = 1
    deductions = 0
    while pg > 0:
        not_possible = set()
        pg = 0

        for pos in possible:
            tmp = validDict[pos]
            if len(tmp) == 1:
                pg += 1
                r = next(iter(tmp))
                grid = grid[:pos] + r + grid[pos+1:]
                remove(pos,r,validDict)
                not_possible.add(pos)
                added[pos] = validDict[pos]

        possible -= not_possible
        not_possible = set()

        # Main speedup
        for grp in aGroups:
            indexd = {}
            # poss = set()
            for pos in grp:
                if grid[pos] != '.': continue
                # if pos not in possible: continue
                lp = validDict[pos]
                # indexd.update({possibility:(pos if possibility not in indexd else -1) for possibility in lp})
                for possibility in lp:
                    if possibility in indexd:
                        indexd[possibility] = -1
                        # poss -= {possibility}
                    else:
                        indexd[possibility] = pos
                        # poss.add(possibility)
            for possibility in indexd:
                if indexd[possibility] == -1: continue
                pos = indexd[possibility] 
                pg += 1
                r = possibility
                grid = grid[:pos] + r + grid[pos+1:]
                # if pos in validDict: added[pos] = validDict[pos]
                remove(pos,r,validDict)
                not_possible.add(pos)
                added[pos] = validDict[pos]

        deductions += pg
        possible -= not_possible

    return grid,deductions,added

def bruteForce(grid,validDict):
    global possible

    grid,deductions,added = deduct(grid,validDict)
    guesses = 0
    pos = selectCell(validDict)

    if pos < 0: return grid,guesses,deductions
    vn = validDict[pos].copy()
    possible.remove(pos)
    for c in validDict[pos]:
        cd = {k:validDict[k].copy() for k in validDict}
        guesses += 1

        remove(pos,c,cd)
        bf,g,deductions = bruteForce(grid[:pos] + c + grid[pos+1:],cd)
        guesses += g
        if bf != "": return bf,guesses,deductions
    possible.add(pos)
    possible.update(added)
    return "",guesses,deductions

def prevalidate(grid,pv):
    
    global possible
    possible = {pos for pos in range(grid_len) if grid[pos] == "."}

    validDict = {}

    for i in possible:
        validDict[i] = validNext(grid,i)
        posWithIndex[i] = set()
        for grp in aGroups:
            if i in grp:
                for pos in grp:
                    posWithIndex[i].add(pos)
        # posWithIndex[i] -= {str(i)}

    grid,deductions,added = deduct(grid,validDict)
    return grid,deductions,validDict

def main():
    try:
        if len(sys.argv) < 2 or len(sys.argv[1]) != 81: raise RuntimeError('500 Invalid arguments')
        oldgrid = sys.argv[1]
        possibleValues = list(range(1,10))
        grid,deductions,validDict = prevalidate(oldgrid,possibleValues[:])
        bf,guesses,tempd = bruteForce(grid,validDict)
        if not validate(bf):
            raise RuntimeError('500 Algorithm produced an invalid board ({})'.format(i+1))
        else:
            print(bf)
        return
    except RuntimeError as e:
        print(e)
    except Exception:
        print('500 Unknown error')
main()
