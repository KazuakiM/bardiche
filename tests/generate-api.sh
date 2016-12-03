#!/bin/sh
#--------------------------------
# @copyright KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
# @author    KazuakiM <kazuaki_mabuchi_to_go@hotmail.co.jp>
# @license   http://www.opensource.org/licenses/mit-license.php  MIT License
#
# @link      https://github.com/KazuakiM/bardiche
#--------------------------------
cp tests/src/index.html /tmp/build/                                        > /dev/null 2>&1 && \
cd /tmp/build/                                                             > /dev/null 2>&1 && \
git config --global user.email "travis@travis-ci.org"                      > /dev/null 2>&1 && \
git config --global user.name  "Travis"                                    > /dev/null 2>&1 && \
git init                                                                   > /dev/null 2>&1 && \
git remote add origin https://${GH_TOKEN}@github.com/KazuakiM/bardiche.git > /dev/null 2>&1 && \
git checkout -B gh-pages                                                   > /dev/null 2>&1 && \
git add --all                                                              > /dev/null 2>&1 && \
git commit -m "API updated"                                                > /dev/null 2>&1 && \
git push --force --quiet  origin gh-pages                                  > /dev/null 2>&1
