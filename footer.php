                                
    
                            </div><?php box( (!$bI) ? "comments" : "social" ) ?>
                            
                            
                        </div>
                        
                        <div class="clear"></div>
                        
                    </div>
                    
                </div>
                
            </div>
                
            <div class="clear"></div>
            
            <div class="bar" id="footer">
                
                Copyright &copy; <?=date("Y") . " " . NAME?>
                
                <div class="author">Design by <a href="mailto:gabriel@terrasoftlabs.com?subject=<?=urlencode(NAME)?>%20Site" title="Email the Designer">Gabriel Nahmias</a></div>
                
                <?php if ($bI): ?><div class="desktop"><a href="index.php?d" title="Visit the Desktop Version of the Site">Desktop Site</a></div><?php endif; ?>
                
            </div>
            
        </div>
        
	</div>

</body>

<script language="javascript" src="<?=TEXT_MIN_F . DIR_JS_LOGIC . "/footer.js"?>" type="text/javascript"></script>

</html>