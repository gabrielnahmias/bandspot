                                
    
                            </div><?php box( (!$bI) ? "comments" : "social" )
							
							//box( (!$bI) ? ( ($pg != "404") ? "comments" : "" ) : "social" )
							
							?>
                            
                            
                        </div>
                        
                        <div class="clear"></div>
                        
                    </div>
                    
                </div>
                
            </div>
            
            <div class="clear"></div>
            
            <div class="bar" id="footer">
                
                Copyright &copy; <?=date("Y") . " " . NAME?>
                
                <div class="author">Site by <a href="mailto:gabriel@terrasoftlabs.com?subject=<?=urlencode(NAME)?>%20Site" title="Email the Designer">Gabriel Nahmias</a></div>
                
                <a href="<?=URL_TERRASOFT?>" target="_blank" title="Visit Terrasoft's Site"><img src="img/ts.png" /></a>
                
                <?php if ($bI): ?><div class="desktop"><a href="index.php?d" title="Visit the Desktop Version of the Site">Desktop Site</a></div><?php endif; ?>
                
            </div>
            
        </div>
        
	</div>
    
</body>

</html>